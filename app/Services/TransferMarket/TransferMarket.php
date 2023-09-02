<?php

namespace App\Services\TransferMarket;

class TransferMarket
{
    private function getHttpClient()
    {
        return \Http::baseUrl(env('TRANSFERMARKET_API_URL'))
            ->asJson()
            ->acceptJson()
            ->withHeaders([
                'X-RapidAPI-Host' => env('TRANSFERMARKET_API_HOST'),
                'X-RapidAPI-Key' => env('TRANSFERMARKET_API_KEY')
            ]);
    }

    public function getPlayerStats(string $playerId)
    {
        $response = $this->getHttpClient()->get(
            "/players/get-performance?domain=com&id=$playerId",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch player: " . $response->json('message'));
        }

        return $response->json();
    }

    public function searchPlayers(string $player)
    {
        $response = $this->getHttpClient()->get(
            "/search?domain=com&query=$player",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch player: " . $response->json('message'));
        }

        return $response->json();
    }
}
