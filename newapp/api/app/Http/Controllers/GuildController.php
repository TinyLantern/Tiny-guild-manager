<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Guild;
use App\Models\GuildLogo;
use \App\Models\Character;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class GuildController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'guild_name' => 'required|string|max:15',
            'server_id' => 'required|integer',
            'character_id' => 'required|integer',
            'guild_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);
        DB::beginTransaction();
        try {
            $guild = Guild::create([
                'name' => $request->guild_name,
                'server_id' => $request->server_id,
                'character_id' => $request->character_id,
            ]);
            if ($request->hasFile('guild_logo')) {
                $filePath = $request->file('guild_logo')->store('guild_logos', 'public');

                GuildLogo::create([
                    'guild_id' => $guild->id,
                    'file_path' => $filePath,
                ]);
            }

            $character = Character::findOrFail($request->character_id);
            $character->guild_rank_id = 1;
            $character->guild_id = $guild->id;
            $character->save();
            DB::commit();

            return response()->json(['message' => 'Guild created successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['error' => 'Failed to create guild.'], 500);
        }
    }

    public function getGuildInfo()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $character = Character::find(Auth::user()->last_active_character_id);

            if (!$character || !$character->guild_id) {
                return response()->json(['error' => 'Character not in a guild.'], 404);
            }

            $guild = Guild::with(['server', 'latestGuildLogo'])
                ->findOrFail($character->guild_id);

            $response = [
                'name' => $guild->name,
                'server' => $guild->server->name,
                'logo' => $guild->latestGuildLogo ? asset('storage/' . $guild->latestGuildLogo->file_path) : null,
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch guild info: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch guild info.'], 500);
        }
    }

    public function uploadGuildLogo(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:4000',
        ]);

        $character = Character::find(Auth::user()->last_active_character_id);

        if (!$character || !$character->guild_id) {
            return response()->json(['error' => 'Character not in a guild.'], 404);
        }

        if ($character->guild_rank_id !== 1) {
            return response()->json(['error' => 'Only the guildmaster can upload a logo.'], 403);
        }

        DB::beginTransaction();
        try {
            $filePath = $request->file('logo')->store('guild_logos', 'public');

            $guildLogo = GuildLogo::create([
                'guild_id' => $character->guild_id,
                'file_path' => $filePath,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Logo uploaded successfully!',
                'logoUrl' => asset('storage/' . $filePath),
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to upload guild logo: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to upload guild logo.'], 500);
        }
    }
}