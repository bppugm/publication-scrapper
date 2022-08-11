<?php

namespace App\Clients;

use GuzzleHttp\Client;

class EbscoClient
{
    public $client;
    public $auth;
    public $dbs = ['a9h'];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://eit.ebscohost.com/',
        ]);

        $this->auth = [
            'prof' => config('services.ebsco.profile'),
            'pwd' => config('services.ebsco.password')
        ];
    }

    public function search($query = [])
    {
        $query = array_merge($query, $this->auth);
        $response = $this->client->get('Services/SearchService.asmx/Search', [
            'query' => $query
        ]);

        $xml = $response->getBody()->getContents();
        return simplexml_load_string($xml);
    }
}
