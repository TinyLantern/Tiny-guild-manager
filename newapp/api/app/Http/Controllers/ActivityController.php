<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Character;
use App\Models\Activity;

class ActivityController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:255',
            'start_time' => 'required|date_format:Y-m-d\TH:i',
            'end_time' => 'required|date_format:Y-m-d\TH:i',
            'dkp_points' => 'required|integer',
        ]);        
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
            if (!$activeCharacter || !$activeCharacter->guild_id) {
                return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
            }
            if($activeCharacter->guild_rank_id == 4){
                return response()->json(['error' => 'Forbidden: Unauthorized'], 403);
            }
            $guild_id = $activeCharacter->guild_id;

            $start_time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time)->format('Y-m-d H:i:s');
            $end_time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time)->format('Y-m-d H:i:s');

            Log::info($request->name);
            Log::info($request->description);
            Log::info($start_time);
            Log::info($end_time);
            Log::info($request->dkp_points);
            Log::info($guild_id);

            $activity = Activity::create([
                'name' => $request->name,
                'description' => $request->description,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'dkp' => $request->dkp_points,
                'guild_id' => $guild_id,
            ]);

            DB::commit();

            return response()->json(['message' => 'Activity created successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to create activity.'], 500);
        }
    }

    public function index()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
        $guild_id = $activeCharacter->guild_id;
        
        $activities = Activity::where('guild_id', $guild_id)
            ->get(['id', 'name', 'description', 'start_time', 'end_time', 'dkp']);

        return response()->json($activities, 200);
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }

        $guild_id = $activeCharacter->guild_id;

        $activity = Activity::where('id', $id)
            ->where('guild_id', $guild_id)
            ->first();

        if (!$activity) {
            return response()->json(['error' => 'Activity not found or not part of your guild.'], 404);
        }

        return response()->json($activity, 200);
    }

    public function upcommingActivities()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
        $guild_id = $activeCharacter->guild_id;

        $activities = Activity::where('guild_id', $guild_id)
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->limit(10)
            ->get(['id', 'name', 'description', 'start_time', 'end_time', 'dkp']);

        return response()->json($activities, 200);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
    
        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
    
        if ($activeCharacter->guild_rank_id == 4) {
            return response()->json(['error' => 'Forbidden: Unauthorized'], 403);
        }
    
        $activity = Activity::where('id', $id)->where('guild_id', $activeCharacter->guild_id)->first();
    
        if (!$activity) {
            return response()->json(['error' => 'Activity not found or unauthorized'], 404);
        }
    
        $activity->delete();
    
        return response()->json(['message' => 'Activity deleted successfully'], 200);
    }
    
}
