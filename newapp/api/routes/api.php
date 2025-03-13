<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\CharTypeController;
use App\Http\Controllers\WeaponController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\GuildController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuildApplicationController;
use App\Http\Controllers\GuildRankController;
use App\Http\Controllers\DiscordController;
use App\Http\Controllers\StaticTeamController;
use Illuminate\Support\Facades\Log;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('/auth/discord', [UserController::class, 'login']);
Route::get('/weapons', [WeaponController::class, 'list']);
Route::get('/character-type', [CharTypeController::class, 'list']);
Route::get('/guild-ranks', [GuildRankController::class, 'list']);

Route::post('/discord/interactions', [DiscordController::class, 'handleDiscordInteraction']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/createcharacter', [CharacterController::class, 'store']);
    Route::get('/characters/{id}', [CharacterController::class, 'show']);
    Route::put('/characters/{id}', [CharacterController::class, 'update']);
    Route::post('/createguild', [GuildController::class, 'store']);
    Route::post('/createguildapplication', [GuildApplicationController::class, 'store']);
    Route::get('/characters', [CharacterController::class, 'index']);
    Route::get('/guildcharacters', [CharacterController::class, 'guildMemberIndex']);
    Route::get('/mycharacters', [CharacterController::class, 'myCharacters']);
    Route::get('/characterapplication', [CharacterController::class, 'indexWithoutGuild']);
    Route::get('/servers', [ServerController::class, 'list']);
    Route::post('/setactivecharacter', [UserController::class, 'setActiveCharacter']);
    Route::get('/servers/{server}/guilds', [ServerController::class, 'getGuilds']);
    Route::get('/user/last-active-character', [UserController::class, 'getLastActiveCharacter']);
    Route::get('/guild-applications', [GuildApplicationController::class, 'index']);
    Route::post('/accept-application/{id}', [GuildApplicationController::class, 'acceptApplication']);
    Route::post('/decline-application/{id}', [GuildApplicationController::class, 'declineApplication']);
    Route::get('/guild-character-counts', [CharacterController::class, 'getGuildCharacterCounts']);
    Route::post('/createactivity', [ActivityController::class, 'store']);
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::get('/activities/{id}', [ActivityController::class, 'show']);
    Route::post('/activity-participants', [ActivityParticipantController::class, 'store']);
    Route::get('/activities/{id}/participants', [ActivityParticipantController::class, 'getParticipants']);
    Route::get('/activities/{activity_id}/unresponded', [ActivityParticipantController::class, 'getUnrespondedMembers']);
    Route::post('/activities/{id}/post-discord', [DiscordController::class, 'postToDiscord']);
    Route::get('/members-for-parties', [CharacterController::class, 'membersForParties']);
    Route::get('/teams', [StaticTeamController::class, 'index']);
    Route::post('/teams', [StaticTeamController::class, 'store']);
    Route::delete('/teams/{id}', [StaticTeamController::class, 'destroy']);
    Route::post('/team-members', [StaticTeamController::class, 'addMemberToTeam']);
    Route::delete('/team-members', [StaticTeamController::class, 'removeMemberFromTeam']);
    Route::get('/upcomming-activities', [ActivityController::class, 'upcommingActivities']);
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);
    Route::delete('/characters/{id}', [CharacterController::class, 'destroy']);
    Route::put('/characters/{id}/remove-from-guild', [CharacterController::class, 'removeFromGuild']);
    Route::post('/characters/{character}/upload-gear-picture', [CharacterController::class, 'uploadGearPicture']);
    Route::get('/guild-info', [GuildController::class, 'getGuildInfo']);
    Route::post('/upload-guild-logo', [GuildController::class, 'uploadGuildLogo']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-log', function () {
    Log::info('Testovací log vytvorený');
    return 'Log vytvorený';
});