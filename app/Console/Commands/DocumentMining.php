<?php

namespace App\Console\Commands;

use App\Document;
use App\Http\Resources\DocumentsTransformer;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class DocumentMining extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:scrap {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and store Scopus document on the given year';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $years = $this->argument('year') ? explode(',', $this->argument('year')) : [now()->year];

        $this->clearData($years);

        foreach ($years as $year) {
            $documents = $this->getDocuments($year);
            Document::insert($documents);
        }
    }

    protected function clearData($years)
    {
        if ($years) {
            foreach ($years as $year) {
                Document::where('published_at', 'like', "%$year%")->delete();
                $this->warn("Documents in $year have been cleared");
            }
        } else {
            Document::truncate();
        }
    }

    protected function getDocuments($year = null)
    {
        $this->info('Fetching documents in year '.$year);
        $documents = [];
        $start = 0;

        do {
            $response = $this->getResponse($this->getUrl(), $this->getQuery($year, ['start' => $start]));

            $response = json_decode($response->getBody(), true);

            $end = $this->getTotalRecord($response);
            $start = $start + 25;

            $entry = $this->getResponseData($response);
            $documents = array_merge($documents, $entry);
        } while ($start < $end);

        return $documents;
    }

    protected function getResponse($url, $query = [])
    {
        $response = $this->client->get($url, [
            'query' => $query,
        ]);

        return $response;
    }

    protected function getUrl()
    {
        return 'https://api.elsevier.com/content/search/scopus';
    }

    protected function getQuery($year = 2013, $additional = [])
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

    protected function getResponseData($responseArray)
    {
        return DocumentsTransformer::collection(collect($responseArray['search-results']['entry']))->jsonSerialize();
    }

    protected function getTotalRecord($responseArray)
    {
        return $responseArray['search-results']['opensearch:totalResults'];
    }
}
