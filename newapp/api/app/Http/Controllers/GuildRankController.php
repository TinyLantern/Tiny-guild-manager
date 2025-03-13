<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuildRank;

class GuildRankController extends Controller
{
    public function list(Request $request)
    {
        $request = GuildRank::all();

        return response()->json($request);
    }
}
