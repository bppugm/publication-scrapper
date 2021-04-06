<?php

namespace App\Clients;

use GuzzleHttp\Client;
use App\Formatters\FormatterInterface;

class MicrosoftAcademicClient  
{
    protected $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => 'https://api.labs.cognitive.microsoft.com/academic/v1.0/',
            'headers' => [
                'Ocp-Apim-Subscription-Key' => config('app.ma_api_key')
            ]
        ]);
    }

    public function evaluate(array $params = [], $year = 2019)
    {
        $params = array_merge( 
            [
                'expr' => "AND(Composite(AA.AfN=='gadjah mada university'),Y=$year) ",
                'attributes' => 'Id,Ti,Ty,Y,CC,J.JN,J.JId,AA.AuN,AA.AuId,AA.AfN,AA.AfId,C.CN,DOI,Pt',
                'offset' => 0,
                'count' => 100,
            ], $params
        );

        $reponse = $this->client->post('evaluate', [
            'form_params' => $params
        ]);

        $body = json_decode($reponse->getBody(), true);

        return $body;
    }
}
