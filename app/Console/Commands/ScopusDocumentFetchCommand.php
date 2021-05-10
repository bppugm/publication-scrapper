<?php

namespace App\Console\Commands;

use App\Author;
use App\AuthorDocument;
use App\Document;
use finfo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ScopusDocumentFetchCommand extends Command
{
    protected $client;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scopus_document:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            'base_uri' => 'https://api.elsevier.com/content/search/scopus',
            'headers' => [
                'X-ELS-APIKey' => env('ELSEVIER_API_KEY')
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
        // AuthorDocument::truncate();

        $body = [
            'query' => 'pubdatetxt(2015) AND AF-ID(60069380)',
            'field' => 'authid,authname,afid,dc:identifier,dc:title,prism:doi,subtypeDescription,prism:publicationName,prism:coverDate,prism:doi,source-id,author-url,prism:coverDate',
            'count' => 100,
            // 'view' => 'complete',
            'start' => 0
        ];

        $count = true;

        while ($count != 0) {
            $json = $this->client->request('GET', '', [
                'query' => $body
            ])->getBody()->getContents();

            $response = json_decode($json, true);

            $count = $response["search-results"]["opensearch:totalResults"];

            if ($count == 0) {
                return;
            }

            foreach ($response["search-results"]["entry"] as $key => $document) {
                $document = optional($document);
                $authors = [];
                foreach ($document['author'] as $key => $author) {

                    $faculty = optional(Author::where('author_id', $author['authid'])->first())->faculty;

                    $item = [
                        'index' => $key+1,
                        'url' => $author['author-url'],
                        'authorname' => $author['authname'],
                        'authid' => $author['authid'],
                        'faculty' => $faculty,
                    ];

                    if (key_exists('afid', $author)) {
                        $item['affiliations'] = collect($author['afid'])->map(function ($aff)
                        {
                            return $aff['$'];
                        })->toArray();
                        $isUgm = collect($item['affiliations'])->contains('60069380');
                        $item['is_ugm'] = $isUgm;
                    }

                    if ($faculty == null) {
                        AuthorDocument::create($item);
                    }

                    $authors[] = $item;
                }

                $faculties = collect($authors)->map(function ($item)
                {
                    return optional($item)['faculty'];
                })->unique()
                ->filter(function ($item)
                {
                    return $item != null;
                })->implode(',');

                $record = Document::firstOrCreate([
                    'identifier' => $document["dc:identifier"],
                    'doi' => $document['prism:doi'],
                ],
                [
                    'year' => substr($document['prism:coverDate'], 0, 4),
                    'doi' => $document['prism:doi'],
                    'title' => $document['dc:title'],
                    'url' => $document['prism:url'],
                    'authors' => $authors,
                    'faculties' => $faculties
                ]);

                $this->info($record->title." Created [{$record->year}]");
            }

            $body['start'] = $body['start'] + 100;
        }


    }
}
