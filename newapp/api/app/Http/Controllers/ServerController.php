<?php

namespace App\Http\Controllers;
use App\Models\Server;
use App\Models\Guild;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function list(Request $request)
    {
        $request = Server::all();

        return response()->json($request);
    }

    public function getGuilds($serverId)
    {
        $guilds = Guild::where('server_id', $serverId)->get(['id', 'name']);
        return response()->json($guilds);
    }
}
