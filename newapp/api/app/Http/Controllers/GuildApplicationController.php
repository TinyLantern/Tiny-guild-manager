<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Character;
use App\Models\Guild;
use App\Models\GuildApplication;

class GuildApplicationController extends Controller
{
    public function store (Request $request){
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'character_id' => 'required|integer',
            'guild_id' => 'required|integer',
        ]);

        DB::beginTransaction();
        try{
            $existingApplication = GuildApplication::where([
                ['character_id', '=', $request->character_id],
                ['guild_id', '=', $request->guild_id],
                ['guild_application_status_id', '=', 1],
            ])->first();
    
            if ($existingApplication) {
                return response()->json(['error' => 'An application with the same character and guild already exists.'], 409);
            }

            GuildApplication::create([
                'character_id' => $request->character_id,
                'guild_id' => $request->guild_id,
                'guild_application_status_id' => 1,
            ]);

            DB::commit();
            return response()->json(['message' => 'Application registered successfully!'], 201);
        }
        catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to register character.'], 500);
        }
    }

    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();

        $activeCharacter = $user->last_active_character_id ? Character::with('guildRank')->find($user->last_active_character_id) : null;

        if (!$activeCharacter || !in_array($activeCharacter->guildRank->rank ?? '', ['Advisor', 'Guardian', 'Guild Master'])) {
            return response()->json(['error' => 'Forbidden: Access denied'], 403);
        }
    
        $guildApplications = GuildApplication::with([
            'character.belongsToUser',
            'character.characterType',
            'guild',
            'guild_application_status'
        ])
        ->whereHas('guild', function ($query) use ($activeCharacter) {
            $query->where('character_id', $activeCharacter->id);
        })
        ->get()
        ->map(function ($application) {
            $discordName = $application->character->belongsToUser->discord_global_name ?: $application->character->belongsToUser->discord_username;
            return [
                'application_id' => $application->id,
                'discord' => $discordName ?? 'Unknown',
                'character' => $application->character->character_name ?? 'Unknown',
                'role' => $application->character->characterType->type ?? 'Unknown',
                'gear' => $application->character->gear_score,
                'appliedOn' => $application->created_at->toDateString(),
                'lastUpdated' => $application->updated_at->toDateString(),
                'status' => $application->guild_application_status->status ?? 'Unknown',
            ];
        });
    
        return response()->json($guildApplications, 200);
    }

    public function acceptApplication($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $user = Auth::user();
    
        $activeCharacter = $user->last_active_character_id ? Character::with('guildRank')->find($user->last_active_character_id) : null;
    
        if (!$activeCharacter || !in_array($activeCharacter->guildRank->rank ?? '', ['Advisor', 'Guardian', 'Guild Master'])) {
            return response()->json(['error' => 'Forbidden: Access denied'], 403);
        }
    
        $application = GuildApplication::find($id);
    
        if (!$application) {
            return response()->json(['error' => 'Application not found'], 404);
        }
    
        DB::beginTransaction();
        try {
            $application->guild_application_status_id = 2;
            $application->save();
    
            $character = $application->character;
    
            if ($character) {
                $guild = $application->guild;
    
                if (!$guild) {
                    throw new \Exception('Guild not found.');
                }
    
                $character->guild_id = $guild->id;
    
                $defaultGuildRankId = 4;
                $character->guild_rank_id = $defaultGuildRankId;
                $character->save();
            }
    
            DB::commit();
            return response()->json(['message' => 'Application accepted, character added to the guild.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to accept application: ' . $e->getMessage()], 500);
        }
    }
    

    public function declineApplication($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        DB::beginTransaction();
        try {
            $application = GuildApplication::findOrFail($id);

            $user = Auth::user();
            $activeCharacter = $user->last_active_character_id ? Character::with('guildRank')->find($user->last_active_character_id) : null;

            if (!$activeCharacter || !in_array($activeCharacter->guildRank->rank ?? '', ['Advisor', 'Guardian', 'Guild Master'])) {
                return response()->json(['error' => 'Forbidden: Access denied'], 403);
            }

            $application->update([
                'guild_application_status_id' => 3,
            ]);

            DB::commit();
            return response()->json(['message' => 'Application declined successfully.'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Failed to decline application.'], 500);
        }
    }
}
