<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Character;
use App\Models\Guild;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function login(Request $request)
    {
        if (!$request->has('code')) {
            return response()->json(['error' => 'No code provided'], 400);
        }

        $discord_code = $request->query('code');
        $payload = [
            'code' => $discord_code,
            'client_id' => env('ClientID'),
            'client_secret' => env('ClientSecret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost:8101/login',
            'scope' => 'identify guilds',
        ];

        Log::info("Payload: ", $payload);
        $response = Http::asForm()->post('https://discord.com/api/v10/oauth2/token', $payload);

        if ($response->successful()) {
            Log::info("Response successful:");
            $response_data = $response->json();
            $access_token = $response_data['access_token'];
            $discord_users_url = "https://discord.com/api/users/@me";
            
            $resp = Http::withToken($access_token)->get($discord_users_url);
            if ($resp->successful()) {
                $user_data = $resp->json(); 
                Log::info("User Info:", $user_data);

                $discord_id = $user_data['id'];
                $discord_global_name = $user_data['global_name'] ?? '';
                $discord_avatar = $user_data['avatar'] ?? '';
                $discord_username = $user_data['username'];

                $user = User::where('discord_id', $discord_id)->first();

                if (!$user) {
                    try {
                        $new_user = User::create([
                            'discord_global_name' => $discord_global_name ?? 'Unknown',
                            'discord_username' => $discord_username,
                            'discord_id' => $discord_id,
                            'discord_avatar' => $discord_avatar ?? null,
                            'last_active_character_id' => null,
                        ]);
                    
                        if ($new_user) {
                            Log::info("New user created:", $new_user->toArray());
                            $user = $new_user;
                        } else {
                            Log::error("Failed to create new user in the database");
                        }
                    } catch (\Exception $e) {
                        Log::error("Error creating user: " . $e->getMessage());
                    }
                } else {
                    Log::info("User already exists in the database:", $user->toArray());
                }
                $token = $user->createToken('NewApp')->plainTextToken;

                Log::info("Login successful");
                
                return response()->json([
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user->id,
                        'discord_global_name' => $discord_global_name ?? 'Unknown',
                        'discord_username' => $discord_username,
                        'discord_id' => $discord_id,
                        'discord_avatar' => $discord_avatar ?? null,
                    ],
                    'access_token' => $token,
                ]);
            }
            
            return response()->json(['error' => 'Failed to fetch user information from Discord'], 500);
        }
        
        return response()->json(['error' => 'Failed to get token from Discord'], 500);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        
        if ($user) {
            return response()->json([
                'id' => $user->id,
                'discord_global_name' => $user->discord_global_name,
                'discord_username' => $user->discord_username,
                'discord_id' => $user->discord_id,
                'discord_avatar' => $user->discord_avatar,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function setActiveCharacter(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'character_id' => 'required|exists:characters,id',
        ]);

        try {
            $user = Auth::user();

            $character = Character::with(['guild', 'guildRank'])->findOrFail($request->character_id);
            if ($character->user_id !== $user->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update(['last_active_character_id' => $character->id]);

            $activeCharacter = Character::with(['guildRank'])->find($character->id);
            $guild = Guild::where('character_id', $activeCharacter->id)->first();
    
            return response()->json([
                'message' => 'Active character updated successfully.',
                'character' => [
                    'id' => $character->id,
                    'name' => $character->character_name,
                    'guild' => $character->guild->name ?? null,
                    'guild_rank' => $character->guildRank->rank ?? null,
                ],
            ]);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Failed to update active character.'], 500);
        }
    }

    public function getLastActiveCharacter()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $activeCharacter = Character::with(['guild', 'guildRank'])->find($user->last_active_character_id);
    
            if ($activeCharacter) {
                return response()->json([
                    'character' => [
                        'id' => $activeCharacter->id,
                        'name' => $activeCharacter->character_name,
                        'guild' => $activeCharacter->guild->name ?? null,
                        'guild_rank' => $activeCharacter->guildRank->rank ?? null,
                    ],
                ]);
            }
    
                return response()->json(['character' => null]);
            } catch (\Exception $e) {
                Log::error('Error fetching last active character:', [
                    'message' => $e->getMessage(),
                    'stack' => $e->getTraceAsString(),
                ]);
                return response()->json(['error' => 'Failed to fetch last active character.'], 500);
            }
    }

}