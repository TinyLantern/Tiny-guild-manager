<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DiscordController extends Controller
{
    private function getParticipantData($activity_id)
    {
        $participants = DB::table('activity_participants')
            ->join('characters', 'activity_participants.character_id', '=', 'characters.id')
            ->where('activity_participants.activity_id', $activity_id)
            ->select('characters.character_name', 'activity_participants.activity_participation_status_id')
            ->get();

        return [
            'participating' => $participants->where('activity_participation_status_id', 1)->pluck('character_name')->toArray(),
            'notParticipating' => $participants->where('activity_participation_status_id', 2)->pluck('character_name')->toArray(),
            'tentative' => $participants->where('activity_participation_status_id', 3)->pluck('character_name')->toArray(),
        ];
    }

    private function formatEmbed($title, $description, $participants, $dkp)
    {
        return [
            'title' => $title,
            'description' => $description,
            'fields' => [
                [
                    'name' => 'DKP',
                    'value' => $dkp,
                    'inline' => false,
                ],
                [
                    'name' => 'Participating',
                    'value' => count($participants['participating']) > 0 ? implode("\n", $participants['participating']) : 'No participants yet',
                    'inline' => true,
                ],
                [
                    'name' => 'Not Participating',
                    'value' => count($participants['notParticipating']) > 0 ? implode("\n", $participants['notParticipating']) : 'No responses yet',
                    'inline' => true,
                ],
                [
                    'name' => 'Tentative',
                    'value' => count($participants['tentative']) > 0 ? implode("\n", $participants['tentative']) : 'No tentative responses',
                    'inline' => true,
                ],
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    private function sendDiscordRequest($method, $url, $data)
    {
        $client = new Client();
        $botToken = env('DISCORD_BOT_TOKEN');

        try {
            return $client->request($method, $url, [
                'headers' => [
                    'Authorization' => "Bot $botToken",
                    'Content-Type' => 'application/json',
                ],
                'json' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send request to Discord: " . $e->getMessage());
            return false;
        }
    }

    public function postToDiscord(Request $request, $activity_id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $activity = \App\Models\Activity::find($activity_id);
        if (!$activity) {
            return response()->json(['error' => 'Activity not found.'], 404);
        }

        $participants = $this->getParticipantData($activity_id);
        $embed = $this->formatEmbed($activity->name, $activity->description, $participants, $activity->dkp);

        $buttons = [
            [
                'type' => 1,
                'components' => [
                    ['type' => 2, 'label' => 'Participating', 'style' => 3, 'custom_id' => "participating_$activity_id"],
                    ['type' => 2, 'label' => 'Not Participating', 'style' => 4, 'custom_id' => "not_participating_$activity_id"],
                    ['type' => 2, 'label' => 'Tentative', 'style' => 2, 'custom_id' => "tentative_$activity_id"],
                ],
            ],
        ];

        $response = $this->sendDiscordRequest("POST", "https://discord.com/api/v10/channels/" . env('DISCORD_CHANNEL_ID') . "/messages", [
            'embeds' => [$embed],
            'components' => $buttons,
        ]);

        return $response ? response()->json(['message' => 'Posted to Discord successfully!']) : response()->json(['error' => 'Failed to post to Discord.'], 500);
    }

    public function handleDiscordInteraction(Request $request)
    {
        Log::info('Incoming Discord Interaction Payload:', $request->all());

        if (!$this->verifyDiscordSignature(env('DISCORD_PUBLIC_KEY'), $request->header('X-Signature-Ed25519'), $request->header('X-Signature-Timestamp'), $request->getContent())) {
            Log::error('Invalid Discord signature');
            return response()->json(['error' => 'Invalid request signature'], 401);
        }

        if ($request->input('type') == 1) {
            return response()->json(['type' => 1]);
        }

        if ($request->input('type') == 3) {
            $data = $request->all();
            $customId = $data['data']['custom_id'];
            $userId = $data['member']['user']['id'];
            $username = $data['member']['user']['username'];
            $messageId = $data['message']['id'];
            $channelId = $data['channel_id'];

            $parts = explode('_', $customId);
            $activityId = array_pop($parts);
            $statusKey = implode('_', $parts);
            $statusMap = ['participating' => 1, 'not_participating' => 2, 'tentative' => 3];

            if (!isset($statusMap[$statusKey])) {
                return response()->json(['error' => 'Invalid button'], 400);
            }

            $user = DB::table('users')->where('discord_id', $userId)->first();
            if (!$user) {
                return response()->json(['type' => 4, 'data' => ['content' => 'Please log in to the app through Discord first.', 'flags' => 64]]);
            }

            $character = $user->last_active_character_id ? DB::table('characters')->where('id', $user->last_active_character_id)->first() : null;
            if (!$character) {
                return response()->json(['type' => 4, 'data' => ['content' => 'Please set an active character first.', 'flags' => 64]]);
            }

            $activity = DB::table('activities')->where('id', $activityId)->first();
            if (!$activity || $character->guild_id !== $activity->guild_id) {
                return response()->json([
                    'type' => 4,
                    'data' => [
                        'content' => 'Your active character is not associated with the guild running this activity, switch your active character or ask for an invite to the guild.',
                        'flags' => 64,
                    ],
                ]);
            }

            DB::table('activity_participants')->updateOrInsert(
                ['activity_id' => $activityId, 'character_id' => $character->id],
                ['activity_participation_status_id' => $statusMap[$statusKey], 'updated_at' => now()]
            );

            $participants = $this->getParticipantData($activityId);
            $embed = $this->formatEmbed($data['message']['embeds'][0]['title'], $data['message']['embeds'][0]['description'], $participants, $activity->dkp);

            $this->sendDiscordRequest("PATCH", "https://discord.com/api/v10/channels/$channelId/messages/$messageId", ['embeds' => [$embed]]);

            return response()->json(['type' => 4, 'data' => ['content' => "**$username** updated their participation status!", 'flags' => 64]]);
        }

        return response()->json(['error' => 'Unsupported interaction'], 400);
    }

    private function verifyDiscordSignature($publicKey, $signature, $timestamp, $body)
    {
        if (!$signature || !$timestamp || !$body) {
            return false;
        }

        return \sodium_crypto_sign_verify_detached(hex2bin($signature), $timestamp . $body, hex2bin($publicKey));
    }
}