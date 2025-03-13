<?php

namespace App\Http\Controllers;
use App\Models\Weapon;
use Illuminate\Http\Request;

class WeaponController extends Controller
{
    public function list(Request $request)
    {
        $request = Weapon::all();

        return response()->json($request);
    }
}
