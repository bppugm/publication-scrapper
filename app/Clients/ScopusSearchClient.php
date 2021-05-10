<?php

namespace App\Clients;

use GuzzleHttp\Client;

class ScopusSearchClient
{
    protected $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.elsevier.com/content/search/scopus',
            'headers' => [
                'X-ELS-APIKey' => env('ELSEVIER_API_KEY')
            ]
        ]);
    }

    public function search()
    {
        $body = [
            'query' => 'pubdatetxt(2015) AF-ID(60069380)',
            'field' => 'authid,authname,afid,dc:identifier,dc:title,prism:doi,subtypeDescription,prism:publicationName,prism:coverDate,prism:doi,source-id',
            'count' => 100,
            // 'view' => 'complete',
            'start' => 0
        ];

        $response = $this->client->request('GET', '', [
            'query' => $body
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
