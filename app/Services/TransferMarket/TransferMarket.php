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

    public function getPlayerStats(string $playerId, ?int $seasonId)
    {
        $response = $this->getHttpClient()->get(
            "/players/get-performance-summary?domain=com&id=$playerId&seasonID=$seasonId",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch player: " . $response->json('message'));
        }

        return $response->json();
    }

    public function search(string $player)
    {
        $response = $this->getHttpClient()->get(
            "/search?domain=com&query=$player",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch player: " . $response->json('message'));
        }

        return $response->json();
    }

    public function getCompetitions()
    {
        $response = $this->getHttpClient()->get(
            "/competitions/list-default?domain=com",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch competitions: " . $response->json('message'));
        }

        return $response->json();
    }

    public function getSeasons(string $league)
    {
        $response = $this->getHttpClient()->get(
            "/competitions/list-seasons?domain=com&id=$league",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch seasons: " . $response->json('message'));
        }

        return $response->json();
    }

    public function getTeams(string $league, int $seasonId)
    {
        $response = $this->getHttpClient()->get(
            "/competitions/get-table?domain=com&id=$league&seasonID=$seasonId",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch teams: " . $response->json('message'));
        }

        return $response->json();
    }

    public function getSquad(int $clubId, ?int $seasonId)
    {
        $response = $this->getHttpClient()->get(
            "/clubs/get-squad?domain=com&id=$clubId&saison_id=$seasonId",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch squad: " . $response->json('message'));
        }

        return $response->json();
    }

    public function getMarketValue(int $playerId)
    {
        $response = $this->getHttpClient()->get(
            "/players/get-market-value?domain=com&id=$playerId",
        );

        if (!$response->successful()) {
            throw new \Exception("Unable to fetch squad: " . $response->json('message'));
        }

        return $response->json();
    }
}
