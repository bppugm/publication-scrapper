<?php

use App\Document;
use App\Http\Resources\DocumentsTransformer;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
	protected $client;
    protected $years;

	function __construct($years = [])
	{
		$this->setClient();
        $this->years = [2017, 2018];
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
    	$this->clearData($this->years);

    	$years = $this->getYears();

        foreach ($years as $year) {
            $documents = $this->getDocuments($year);
        	Document::insert($documents);
        }
    }

    public function clearData($years)
    {
        if ($years) {
            foreach ($years as $year) {
                Document::where('published_at', 'like', "%$year%")->delete();
            }
        } else {
            Document::truncate();
        }
    }

    public function getYears()
    {
        if ($this->years) {
            return $this->years;
        }

        return [2008, 2009, 2010, 2011, 2012, 2013, 2014, 2015, 2016, 2017, 2018];
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
			'apiKey' => env('ELSEVIER_API_KEY'),
			'field' => 'citedby-count,author,dc:identifier,dc:title,prism:doi,subtypeDescription,prism:publicationName,prism:coverDate,prism:doi,source-id,afid,authkeywords',
			'view' => 'complete',
			'start' => 0,
		];

		$query = array_merge($query, $additional);

    	return $query;
    }

    public function getResponseData($responseArray)
    {
        return DocumentsTransformer::collection(collect($responseArray['search-results']['entry']))->jsonSerialize();
    }

    public function getTotalRecord($responseArray)
    {
    	return $responseArray['search-results']['opensearch:totalResults'];
    }
}
