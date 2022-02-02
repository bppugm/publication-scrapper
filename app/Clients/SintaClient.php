<?php

namespace App\Clients;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class SintaClient
{
    public $client;

    protected $cache_key = "sintaApiKey";

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'http://apisinta.kemdikbud.go.id/',
            'headers' => [
                'Authorization' => "Bearer {$this->getApiKey()}"
            ]
        ]);
    }

    public function auth()
    {
        $client =
        new Client([
            'base_uri' => 'http://apisinta.kemdikbud.go.id/'
        ]);
        $response = $client->post('/consumer/login', [
            'multipart' => [
                [
                    'name' => 'username',
                    'contents' => config('services.sinta.username')
                ],[
                    'name' => 'password',
                    'contents' => config('services.sinta.password')
                ]
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function listJournals($params = [])
    {
        $response = $this->client->get('/v2/journals', [
            'query' => $params
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getApiKey()
    {
        return Cache::rememberForever($this->cache_key, function ()
        {
            $token = $this->auth()['token'];
            return $token;
        });
    }

    public function forgetApiKey()
    {
        Cache::forget($this->cache_key);
    }
}
