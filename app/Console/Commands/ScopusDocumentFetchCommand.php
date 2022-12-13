<?php

namespace App\Console\Commands;

use App\Author;
use App\AuthorDocument;
use App\Clients\ScopusSearchClient;
use App\Document;
use finfo;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ScopusDocumentFetchCommand extends Command
{
    protected $scopus;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scopus_document:fetch
    {year?* : Default to this year. Multiple years are allowed. Seperate multiple years using whitespace.}
    {--flagship= : Research flagship topic. Available options are: food,energy,health,climate}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch scopus documents';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->scopus = new ScopusSearchClient;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Document::truncate();
        AuthorDocument::truncate();

        $years = $this->argument('year') ?: [now()->year];

        $yearQuery = "";
        foreach ($years as $key => $year) {
            if ($key >= 1) {
                $yearQuery .= " OR ";
            }
            $yearQuery .= "(PUBYEAR = $year)";
        }

        $client = $this->scopus->getClient();

        $body = [
            'query' => "($yearQuery) AND AF-ID(60069380)",
            // 'query' => "AF-ID(60069380) AND (PUBYEAR < 2017)",
            'field' => 'authid,authname,given-name,surname,afid,dc:identifier,dc:title,prism:doi,subtypeDescription,prism:publicationName,source-id,author-url,prism:coverDate,prism:issn,subtype,prism:volume,prism:issueIdentifier,prism:pageRange,eid,authkeywords,affilname',
            'count' => 100,
            // 'view' => 'complete',
            'start' => 0
        ];

        if ($this->option('flagship')) {
            $body['query'] = $body['query']." AND {$this->scopus->getQuery($this->option('flagship'))}";
        }

        $count = true;
        while ($count != 0) {
            $json = $client->request('GET', '', [
                'query' => $body
            ])->getBody()->getContents();

            $response = json_decode($json, true);

            if (array_key_exists('entry', $response["search-results"]) == false) {
                break;
            }

            foreach ($response["search-results"]["entry"] as $key => $document) {
                $document = optional($document);
                $authors = [];
                if ($document['author'] == null) {
                    $document['author'] = [];
                }
                foreach ($document['author'] as $key => $author) {

                    $data = optional(Author::where('author_id', $author['authid'])->first());
                    $name = ltrim("{$author['given-name']} {$author['surname']}");
                    $item = [
                        'index' => $key+1,
                        'url' => $author['author-url'],
                        'authorname' => $name,
                        'authid' => $author['authid'],
                        'faculty' => $data->faculty,
                        'nidn' => $data->nidn,
                        'nip' => $data->nip,
                    ];

                    if (key_exists('afid', $author)) {
                        $item['affiliations'] = collect($author['afid'])->map(function ($aff)
                        {
                            return $aff['$'];
                        })->toArray();
                        $item['affiliation_name'] = collect($item['affiliations'])->map(function ($item) use ($document)
                        {
                            if (count($document['affiliation']) == 0) {
                                return [];
                            }

                            return optional(collect($document['affiliation'])->where('afid', $item)->first())['affilname'];
                        })->implode(',');
                        $isUgm = collect($item['affiliations'])->contains('60069380');
                        $item['is_ugm'] = $isUgm;
                        $item['affiliation_count'] = count($item['affiliations']);
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

                $selectedAuthor = collect($authors)->filter(function ($item) {
                    return optional($item)['nip'];
                })->first();
                $record = Document::firstOrCreate([
                    'identifier' => $document["dc:identifier"],
                    'eid' => $document['eid'],
                ],
                [
                    'year' => substr($document['prism:coverDate'], 0, 4),
                    'date' => $document['prism:coverDate'],
                    'type' => $document['subtypeDescription'],
                    'doi' => $document['prism:doi'],
                    'title' => $document['dc:title'],
                    'source_title' => $document['prism:publicationName'],
                    'issn' => $document['prism:issn'] ? implode("-", str_split($document['prism:issn'], 4)) : '',
                    'vol' => $document['prism:volume'],
                    'issue' => $document['prism:issueIdentifier'],
                    'page' => $document['prism:pageRange'],
                    'keywords' => $document['authkeywords'],
                    'authors' => $authors,
                    'faculties' => $faculties,
                    'selected_author' => optional($selectedAuthor)['authorname'],
                    'selected_nip' => optional($selectedAuthor)['nip'] ? $selectedAuthor['nip'] : '',
                    'selected_nidn' => optional($selectedAuthor)['nidn'],
                    'selected_index' => optional($selectedAuthor)['index'],
                    'selected_af' => optional($selectedAuthor)['affiliation_name'],
                    'selected_af_count' => optional($selectedAuthor)['affiliation_count'],
                ]);
                $this->info($record->title." Created [{$record->year}]");
            }

            $body['start'] = $body['start'] + 100;
        }

        $this->line("");
        $this->line("Extracting unidentified authors");

        $this->call('scopus_author:extract');
        $count = AuthorDocument::count();

        if ($count) {
            $this->call('author_document:export');
            $this->info("$count unidentified authors has been exported.");
        } else {
            $this->info("No unidentified authors found. Documents are ready to be exported.");
        }
    }
}
