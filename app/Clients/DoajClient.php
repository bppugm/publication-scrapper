<?php

namespace App\Clients;

use GuzzleHttp\Client;

class DoajClient
{
    public $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://doaj.org/api/',
        ]);
    }

    public function searchArticle($query = null, $params = [])
    {
        $response = $this->client->get("search/articles/$query", [
            'query' => $params
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
