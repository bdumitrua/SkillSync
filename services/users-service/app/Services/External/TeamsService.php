<?php

namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class TeamsService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.teams_service.url');
    }

    public function getTeamsByUserId(int $id)
    {
        return $this->sendRequest('GET', "/teams/user/{$id}");
    }

    protected function sendRequest(string $method, string $uri, $data = [])
    {
        $token = request()->bearerToken();
        $url = $this->baseUrl . $uri;
        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->retry(3, 100);

        $response = $method === 'GET' ? $request->$method($url) : $request->$method($url, $data);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception('Failed to communicate with the Team Service');
        }
    }
}
