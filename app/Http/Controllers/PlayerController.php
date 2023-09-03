<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function searchPlayers(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|string',
        ]);

        $result = \TransferMarket::searchPlayers($data['player']);

        return response()->json($result);
    }

    public function getPlayerStats(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|string',
            'player_id' => 'required|integer',
        ]);

        $getPlayers = \TransferMarket::searchPlayers($data['player']);

        $selectedPlayer = null;
        foreach ($getPlayers['players'] as $player) {
            if ($player['id'] === $data['player_id']) {
                $selectedPlayer = $player;
                break;
            }
        }

        $result = \TransferMarket::getPlayerStats($selectedPlayer['id']);
        return response()->json($result);
    }
}
