<?php

namespace App\Clients;

use GuzzleHttp\Client;

class OpenAlexClient
{
    public $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => "http://api.openalex.org/"
        ]);
    }

    public function works($query = [])
    {
        $response = $this->client->get('/works', [
            'query' => $query
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
