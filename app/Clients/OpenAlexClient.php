<?php

namespace App\Clients;

use GuzzleHttp\Client;

class OpenAlexClient
{
    public $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => "https://api.openalex.org"
        ]);
    }

    public function works($query = [])
    {
        $response = $this->client->get('/works?filter=institutions.id:I165230279,publication_year:2023', [
            'query' => $query
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
