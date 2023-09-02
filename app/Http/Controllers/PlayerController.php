<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        //
    }

    public function searchPlayers(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|string|min:4',
        ]);

        $result = \TransferMarket::searchPlayers($data['player']);

        return response()->json($result['players']);
    }
}
