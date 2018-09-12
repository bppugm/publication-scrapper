<?php

use App\Document;
use App\Http\Resources\DocumentsTransformer;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
	protected $client;

	function __construct()
	{
		$this->setClient();
	}

	public function setClient()
	{
		$this->client = new Client;
	}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Document::truncate();
    	$years = [2013, 2014, 2015, 2016, 2017];

        foreach ($years as $year) {
            $documents = $this->getDocuments($year);
        	Document::insert($documents);
        }
    }

    public function getDocuments($year = null)
    {
        $documents = [];
        $start = 0;

        do {
            $response = $this->getResponse($this->getUrl(), $this->getQuery($year, ['start' => $start]));

            $response = json_decode($response->getBody(), true);

            $end = $this->getTotalRecord($response);
            $start = $start + 25;

            $entry = $this->getResponseData($response);
            $documents = array_merge($documents, $entry);

        } while ( $start < $end);

        return $documents;
    }

    public function getResponse($url, $query = [])
    {
    	$response = $this->client->get($url, [
    		'query' => $query,
    	]);

    	return $response;
    }

    public function getUrl()
    {
    	return 'https://api.elsevier.com/content/search/scopus';
    }

    public function getQuery($year = 2013, $additional = [])
    {
    	$query = [
			'query' => "PUBYEAR = $year AND AF-ID(60069380)",
			'apiKey' => 'ee76c131d9bf443e5afffa9be30dd723',
			'field' => 'citedby-count,author,dc:identifier,dc:title,prism:doi,subtypeDescription,prism:publicationName,prism:coverDate,prism:doi,source-id,afid',
			'view' => 'complete',
			'start' => 0,
		];

		$query = array_merge($query, $additional);

    	return $query;
    }

    public function getResponseData($responseArray)
    {
        return DocumentsTransformer::collection(collect($responseArray['search-results']['entry']))-> jsonSerialize();
    }

    public function getTotalRecord($responseArray)
    {
    	return $responseArray['search-results']['opensearch:totalResults'];
    }
}
