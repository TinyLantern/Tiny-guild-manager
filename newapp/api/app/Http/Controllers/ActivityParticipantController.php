<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ActivityParticipantController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'activity_id' => 'required|integer|exists:activities,id',
            'character_id' => 'required|integer|exists:characters,id',
            'activity_participation_status_id' => 'required|integer|in:1,2,3'
        ]);
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $character = \App\Models\Character::find($request->character_id);

            if (!$character || $character->user_id !== $user->id) {
                return response()->json(['error' => 'Forbidden: You do not own this character.'], 403);
            }
        $activity = \App\Models\Activity::find($request->activity_id);
        if (!$activity) {
            return response()->json(['error' => 'Activity not found.'], 404);
        }

        $existingParticipation = DB::table('activity_participants')
            ->where('activity_id', $request->activity_id)
            ->where('character_id', $request->character_id)
            ->first();

        if ($existingParticipation) {
            DB::table('activity_participants')
                ->where('activity_id', $request->activity_id)
                ->where('character_id', $request->character_id)
                ->update(['activity_participation_status_id' => $request->activity_participation_status_id]);
        } else {
            DB::table('activity_participants')->insert([
                'activity_id' => $request->activity_id,
                'character_id' => $request->character_id,
                'activity_participation_status_id' => $request->activity_participation_status_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        DB::commit();

        return response()->json(['message' => 'Activity participant registered successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to register activity participant.'], 500);
        }
    }

    public function getParticipants($activity_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $activity = \App\Models\Activity::find($activity_id);
        if (!$activity) {
            return response()->json(['error' => 'Activity not found.'], 404);
        }

        $participants = DB::table('activity_participants')
            ->join('characters', 'activity_participants.character_id', '=', 'characters.id')
            ->join('character_types', 'characters.character_type_id', '=', 'character_types.id')
            ->where('activity_participants.activity_id', $activity_id)
            ->select(
                'characters.id',
                'characters.character_name',
                'activity_participants.activity_participation_status_id',
                'character_types.type as character_role'
            )
            ->get();

        return response()->json($participants);
    }

    public function getUnrespondedMembers($activity_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $activity = \App\Models\Activity::find($activity_id);

        if (!$activity) {
            return response()->json(['error' => 'Activity not found.'], 404);
        }

        $guild_id = $activity->guild_id;

        $allGuildMembers = DB::table('characters')
            ->join('character_types', 'characters.character_type_id', '=', 'character_types.id')
            ->where('guild_id', $guild_id)
            ->select('characters.id', 'characters.character_name', 'character_types.type as character_role') 
            ->get();

        $participatingCharacterIds = DB::table('activity_participants')
            ->where('activity_id', $activity_id)
            ->pluck('character_id')
            ->toArray();

        $unrespondedMembers = $allGuildMembers->reject(function ($member) use ($participatingCharacterIds) {
            return in_array($member->id, $participatingCharacterIds);
        });

        return response()->json([
            'unresponded' => $unrespondedMembers->values(),
        ]);
    }

}