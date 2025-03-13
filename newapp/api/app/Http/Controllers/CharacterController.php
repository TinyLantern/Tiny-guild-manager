<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\User;
use App\Models\GearPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'character_name' => 'required|string|max:16',
            'role' => 'required|integer',
            'primary_weapon' => 'required|integer',
            'secondary_weapon' => 'required|integer',
            'gear_score' => 'nullable|integer',
            'gear_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $character = Character::create([
                'character_name' => $request->character_name,
                'dkp_points' => 0,
                'character_type_id' => $request->role,
                'primary_weapon_id' => $request->primary_weapon,
                'secondary_weapon_id' => $request->secondary_weapon,
                'user_id' => $user->id,
                'gear_score' => $request->gear_score,
                'guild_rank_id' => null,
                'guild_id' => null,
            ]);

            if ($request->hasFile('gear_picture')) {
                $filePath = $request->file('gear_picture')->store('gear_pictures', 'public');

                GearPicture::create([
                    'character_id' => $character->id,
                    'file_path' => $filePath,
                ]);
            }

            $user = User::findOrFail($user->id);
            if ($user->last_active_character_id === null) {
                $user->last_active_character_id = $character->id;
                $user->save();
            }

            DB::commit();

            return response()->json(['message' => 'Character registered successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Failed to register character: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['error' => 'Failed to register character.'], 500);
        }
    }

    public function index()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $characters = Character::where('user_id', Auth::id())
            ->with(['latestGearPicture', 'characterType', 'primaryWeapon', 'secondaryWeapon'])
            ->get()
            ->map(function ($character) {
                return [
                    'id' => $character->id,
                    'username' => $character->character_name,
                    'role' => $character->characterType ? $character->characterType->type : 'Unknown',
                    'gear_picture' => $character->latestGearPicture ? basename($character->latestGearPicture->file_path) : 'No Gear Picture',
                    'dkp_points' => $character->dkp_points,
                    'primaryWeapon' => $character->primaryWeapon ? $character->primaryWeapon->name : 'Unknown',
                    'secondaryWeapon' => $character->secondaryWeapon ? $character->secondaryWeapon->name : 'Unknown',
                    'guild_rank_id' => $character->guild_rank_id,
                    'guild_id' => $character->guild_id,
                    'gear_score' => $character->gear_score,
                ];
            });
    
        return response()->json($characters, 200);
    }

    public function guildMemberIndex()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }

        $guildId = $activeCharacter->guild_id;
        
        $characters = Character::where('guild_id', $guildId)
            ->with(['latestGearPicture', 'characterType', 'primaryWeapon', 'secondaryWeapon', 'guildRank'])
            ->get()
            ->map(function ($character) {
                return [
                    'id' => $character->id,
                    'username' => $character->character_name,
                    'role' => $character->characterType ? $character->characterType->type : 'Unknown',
                    'gear_picture' => $character->latestGearPicture ? basename($character->latestGearPicture->file_path) : 'No Gear Picture',
                    'dkp_points' => $character->dkp_points,
                    'primaryWeapon' => $character->primaryWeapon ? $character->primaryWeapon->name : 'Unknown',
                    'secondaryWeapon' => $character->secondaryWeapon ? $character->secondaryWeapon->name : 'Unknown',
                    'guild_rank' => $character->guildRank->rank,
                    'guild_id' => $character->guild_id,
                    'gear_score' => $character->gear_score,
                ];
            });
    
        return response()->json($characters, 200);
    }

    public function indexWithoutGuild()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $characters = Character::where('user_id', Auth::id())
            ->whereNull('guild_rank_id')
            ->get(['id', 'character_name']);
    
        return response()->json($characters, 200);
    }

    public function myCharacters()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $characters = Character::where('user_id', Auth::id())
            ->get(['id', 'character_name']);
    
        return response()->json($characters, 200);
    }

    public function show($id)
    {
        $character = Character::with([
            'latestGearPicture', 
            'primaryWeapon', 
            'secondaryWeapon',
            'characterType',
            'guildRank',
        ])->findOrFail($id);

        return response()->json([
            'id' => $character->id,
            'discord_username' => $character->discord_username,
            'character_name' => $character->character_name,
            'rank' => $character->guildRank->rank,
            'gear_score'=> $character->gear_score,
            'role' => $character->characterType ? $character->characterType->id : 'Unknown',
            'primary_weapon' => $character->primaryWeapon ? $character->primaryWeapon->id : 'Unknown',
            'secondary_weapon' => $character->secondaryWeapon ? $character->secondaryWeapon->id : 'Unknown',
            'gear_picture' => $character->latestGearPicture ? basename($character->latestGearPicture->file_path) : null,
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'character_name' => 'required|string|max:16',
            'role' => 'required|integer|exists:character_types,id',
            'primary_weapon' => 'required|integer|exists:weapons,id',
            'secondary_weapon' => 'required|integer|exists:weapons,id',
            'gear_score' => 'nullable|integer',
            'guild_rank_id' => 'nullable|integer|exists:guild_ranks,id',
        ]);
    
        try {
            $character = Character::findOrFail($id);
            $user = Auth::user();
            $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

            if (!$activeCharacter || $activeCharacter->guild_id !== $character->guild_id) {
                return response()->json(['error' => 'You are not part of this guild.'], 403);
            }
    
            $isGuildMaster = $activeCharacter->guildRank && $activeCharacter->guildRank->rank === 'Guild Master';

            if (!$isGuildMaster) {
                $character->update([
                    'character_name' => $request->character_name,
                    'character_type_id' => $request->role,
                    'primary_weapon_id' => $request->primary_weapon,
                    'secondary_weapon_id' => $request->secondary_weapon,
                    'gear_score' => $request->gear_score,
                    'guild_rank_id' => $character->guild_rank_id
                ]);
            }
            else {
                $character->update([
                    'character_name' => $request->character_name,
                    'character_type_id' => $request->role,
                    'primary_weapon_id' => $request->primary_weapon,
                    'secondary_weapon_id' => $request->secondary_weapon,
                    'gear_score' => $request->gear_score,
                    'guild_rank_id' => $request->guild_rank_id,
                ]);
            }

            return response()->json(['message' => 'Character updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update character.'], 500);
        }
    }

    public function getGuildCharacterCounts()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }

        $guildId = $activeCharacter->guild_id;

        $characterCounts = Character::where('guild_id', $guildId)
            ->selectRaw("
                COUNT(*) as total_members,
                SUM(CASE WHEN character_type_id = (SELECT id FROM character_types WHERE type = 'Tank') THEN 1 ELSE 0 END) as tanks,
                SUM(CASE WHEN character_type_id = (SELECT id FROM character_types WHERE type = 'DPS') THEN 1 ELSE 0 END) as dps,
                SUM(CASE WHEN character_type_id = (SELECT id FROM character_types WHERE type = 'Healer') THEN 1 ELSE 0 END) as healers
            ")
            ->first();

        return response()->json([
            'active_members' => $characterCounts->total_members,
            'tanks' => $characterCounts->tanks,
            'dps' => $characterCounts->dps,
            'healers' => $characterCounts->healers,
        ], 200);
    }

    public function membersForParties()
    {    
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;

        if (!$activeCharacter || !$activeCharacter->guild_id) {
            return response()->json(['error' => 'Forbidden: No guild associated with this character.'], 403);
        }

        $guildId = $activeCharacter->guild_id;
        
        $characters = Character::where('guild_id', $guildId)
        ->leftJoin('static_team_members', 'characters.id', '=', 'static_team_members.character_id')
        ->whereNull('static_team_members.character_id')
        ->with(['characterType'])
        ->select('characters.*')
        ->get()
        ->map(function ($character) {
            return [
                'id' => $character->id,
                'username' => $character->character_name,
                'role' => $character->characterType ? $character->characterType->type : 'Unknown',
            ];
        });

        return response()->json($characters, 200);
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $character = Character::findOrFail($id);
        $user = Auth::user();

        if ($character->user_id !== $user->id) {
            return response()->json(['error' => 'Forbidden: You do not own this character.'], 403);
        }

        $character->delete();

        return response()->json(['message' => 'Character deleted successfully!'], 200);
    }

    public function removeFromGuild($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $character = Character::findOrFail($id);
        $user = Auth::user();
        $activeCharacter = $user->last_active_character_id ? Character::find($user->last_active_character_id) : null;
    
        if (!$activeCharacter || !in_array($activeCharacter->guild_rank_id, [1, 2])) {
            return response()->json(['error' => 'Forbidden: Only Guild Masters and Advisors can remove members.'], 403);
        }

        $character->update([
            'guild_id' => null,
            'guild_rank_id' => null,
        ]);
    
        return response()->json(['message' => 'Character removed from guild successfully!'], 200);
    }

        
    public function uploadGearPicture(Request $request, Character $character)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'gear_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        try {
            if ($request->hasFile('gear_picture')) {
                $filePath = $request->file('gear_picture')->store('gear_pictures', 'public');

                GearPicture::updateOrCreate(
                    ['character_id' => $character->id],
                    ['file_path' => $filePath]
                );

                return response()->json(['message' => 'Gear picture uploaded successfully!'], 200);
            }

            return response()->json(['error' => 'No file uploaded.'], 400);
        } catch (\Exception $e) {
            Log::error('Failed to upload gear picture: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json(['error' => 'Failed to upload gear picture.'], 500);
        }
    }

}
