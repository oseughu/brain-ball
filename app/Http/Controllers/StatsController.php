<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function getCompetitions()
    {
        $result = \TransferMarket::getCompetitions();

        return response()->json($result);
    }

    public function getSeasons(Request $request)
    {
        $data = $request->validate([
            'league' => 'required|string',
        ]);

        $result = \TransferMarket::getSeasons($data['league']);

        return response()->json($result);
    }

    public function getTeams(Request $request)
    {
        $data = $request->validate([
            'league' => 'required|string',
            'season' => 'required|digits:4',
        ]);

        $result = \TransferMarket::getTeams($data['league'], $data['season']);

        return response()->json($result);
    }

    public function getSquad(Request $request)
    {
        $data = $request->validate([
            'club' => 'required|numeric',
            'season' => 'required|digits:4',
        ]);

        $result = \TransferMarket::getSquad($data['club'], $data['season']);

        return response()->json($result);
    }

    public function getMarketValue(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|numeric',
        ]);

        $result = \TransferMarket::getMarketValue($data['player']);

        return response()->json($result);
    }

    public function searchPlayers(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|string',
        ]);

        $result = \TransferMarket::search($data['player']);

        return response()->json($result['players']);
    }

    public function getPlayerStats(Request $request)
    {
        $data = $request->validate([
            'player' => 'required|string',
            'season' => 'nullable|digits:4',
        ]);

        $result = \TransferMarket::getPlayerStats($data['player'], $data['season'] ?? date("Y"));
        return response()->json($result);
    }
}
