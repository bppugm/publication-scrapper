<?php

namespace App\Clients;

use GuzzleHttp\Client;
use App\Formatters\FormatterInterface;

class MicrosoftAcademicClient  
{
    protected $client;

    public function __construct($apiKey = null) {
        $this->client = new Client([
            'base_uri' => 'https://api.labs.cognitive.microsoft.com/academic/v1.0/',
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $apiKey
            ]
        ]);
    }

    public function evaluate(array $params = [], $year = 2019, FormatterInterface $formatter = null)
    {
        $params = array_merge( 
            [
                'expr' => "AND(Composite(AA.AfN=='gadjah mada university'),Y=$year) ",
                'attributes' => 'Id,C.CId,C.CN,L,Y,Ti,CC,J.JN,J.JId,AA.AuN,AA.AfN,E.DOI',
                'offset' => 0,
                'count' => 100,
            ], $params
        );

        $reponse = $this->client->post('evaluate', [
            'form_params' => $params
        ]);

        $body = json_decode($reponse->getBody(), true);

        if ($formatter) {
            return $formatter->format($body);
        }

        return $body;
    }
}
