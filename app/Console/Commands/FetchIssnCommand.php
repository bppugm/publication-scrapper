<?php

namespace App\Console\Commands;

use App\Document;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchIssnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issn:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch ISSN From BRIN';

    public $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'base_uri' => 'https://apiissn.brin.go.id/api/',
            'headers' => [
                'X-API-KEY' => 'sDefe324b8mdK8perRTy0ZCg'
            ]
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Document::truncate();
        $page = 6866;

        while (true) {
            $this->info("fetching page $page");
            $response = $this->client->get('front/terbitan/list_terbitan', [
                'query' => [
                    'key' => '',
                    'page' => $page
                ]
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            $results = $response['result']['data'];

            if (count($results) == 0) {
                break;
            }

            foreach ($results as $result) {
                $document = Document::create($result);
                $this->info("Created {$document->nama_terbitan}");
            }
            $page++;
        }
    }
}
