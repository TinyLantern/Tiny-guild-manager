<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaticTeam;
use App\Models\StaticTeamMember;
use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class StaticTeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
        $guildId = $activeCharacter->guild_id;
        $teams = StaticTeam::where('guild_id', $guildId)
        ->with(['members' => function ($query) {
            $query->select('characters.id', 'characters.character_name as name', 'character_types.type as role')
                  ->join('character_types', 'characters.character_type_id', '=', 'character_types.id');
        }])
        ->get();

        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:20',
        ]);
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
        $guildId = $activeCharacter->guild_id;

        $team = StaticTeam::create([
            'team_name' => $request->name,
            'guild_id' => $guildId,
        ]);

        return response()->json($team, 201);
    }

    public function destroy($id)
    {           
        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
        $guildId = $activeCharacter->guild_id;

        $team = StaticTeam::findOrFail($id);
        
        if ($team->guild_id !== $guildId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

    public function addMemberToTeam(Request $request)
    {
        $user = Auth::user();
    
        $validated = $request->validate([
            'team_id' => 'required|exists:static_teams,id',
            'character_id' => 'required|exists:characters,id|unique:static_team_members,character_id',
        ]);
    
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
    
        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }
    
        $team = StaticTeam::find($validated['team_id']);
    
        if ($team->guild_id !== $activeCharacter->guild_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($team->members()->count() >= 6) {
            return response()->json(['error' => 'Team is full.'], 400);
        }
    
    
        $teamMember = StaticTeamMember::create([
            'team_id' => $validated['team_id'],
            'character_id' => $validated['character_id'],
        ]);
    
        return response()->json($teamMember, 201);
    }

    public function removeMemberFromTeam(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:static_teams,id',
            'character_id' => 'required|exists:characters,id',
        ]);
    
        $teamMember = StaticTeamMember::where([
            'team_id' => $validated['team_id'],
            'character_id' => $validated['character_id']
        ])->first();
    
        if (!$teamMember) {
            return response()->json(['message' => 'Member not found in team'], 404);
        }
    
        $teamMember->delete();
    
        return response()->json(['message' => 'Member removed from team'], 200);
    }

}