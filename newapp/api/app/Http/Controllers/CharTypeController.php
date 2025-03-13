<?php

namespace App\Http\Controllers;

use App\Models\CharType;
use Illuminate\Http\Request;

class CharTypeController extends Controller
{
    public function list(Request $request)
    {
        $request = CharType::all();

        return response()->json($request);
    }
}
