<?php

namespace App\Console\Commands;

use App\Document;
use App\SimasterAuthor;
use App\Clients\DoajClient;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class DoajFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doaj_document:fetch {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'DOAJ Fetch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $year = $this->argument('year') ?: now()->year;

        $client = new DoajClient;
        Document::truncate();

        $countryQueries = [
            "AND bibjson.journal.country:ID AND bibjson.month:(1 OR 2 OR 3 OR 4 OR 5 OR 6)",
            "AND bibjson.journal.country:ID AND NOT bibjson.month:(1 OR 2 OR 3 OR 4 OR 5 OR 6)",
            "AND NOT bibjson.journal.country:ID",
        ];

        foreach ($countryQueries as $country) {
            $params = [
                'page' => 1,
                'pageSize' => 100
            ];

            $count = 1;

            while ($count) {
                try {
                    $results = $client->searchArticle("bibjson.year:$year AND bibjson.author.affiliation:gadjah Mada $country", $params);
                    $count = count($results['results']);
                    $params['page']++;

                    foreach ($results['results'] as $result) {
                        $result = optional($result);
                        $bibjson = optional($result['bibjson']);
                        $document = [
                            'title' => $bibjson['title'],
                            'doi' => $this->getIdentifier('doi', $result),
                            'journal_title' => optional($bibjson['journal'])['title'],
                            'volume' => optional($bibjson['journal'])['volume'],
                            'no' => optional($bibjson['journal'])['number'],
                            'year' => $bibjson['year'],
                            'lang' => implode(",", optional($bibjson['journal'])['language']),
                            'start_page' => $bibjson['start_page'],
                            'end_page' => $bibjson['end_page'],
                            'p-issn' => $this->getIdentifier('pissn', $result),
                            'e-issn' => $this->getIdentifier('eissn', $result),
                            'link' => optional($bibjson['link'])[0] ? $bibjson['link'][0]['url'] : null,
                            'month' => optional($bibjson)['month'],
                            'date' => now()->setDate((int)$bibjson['year'], (int)optional($bibjson)['month'] ?: 1, 1)->format('Y-m-d'),
                        ];
                        $authors = [];
                        foreach (collect($bibjson['author'])->filter(function ($item) {
                            return Str::contains(strtolower(optional($item)['affiliation']), 'gadjah mada');
                        })->toArray() as $key => $value) {
                            $author = SimasterAuthor::searchText($value['name']);

                            $authors[$key]['name'] = $value['name'];
                            $authors[$key]['afiliasi'] = optional($value)['affiliation'];
                            if ($author) {
                                $authors[$key]['searched_name'] = $author->nama;
                                $authors[$key]['nip'] = $author->nipnika_simaster;
                                $authors[$key]['nidn'] = $author->nomor_registrasi;
                                $authors[$key]['fakultas'] = $author->fakultas;
                                $authors[$key]['score'] = $author->score;
                            }
                        }

                        $document['authors'] = $authors;
                        $document['name'] = collect($document['authors'])->filter(function ($value) {
                            return optional($value)['nip'];
                        })->pluck('name')->first();
                        $document['first_name'] = collect($document['authors'])->pluck('searched_name')->filter(function ($value) {
                            return $value;
                        })->first();
                        $document['first_nip'] = collect($document['authors'])->pluck('nip')->filter(function ($value) {
                            return $value;
                        })->first();
                        $document['first_nidn'] = collect($document['authors'])->pluck('nidn')->filter(function ($value) {
                            return $value;
                        })->first();
                        $document['first_score'] = collect($document['authors'])->pluck('score')->filter(function ($value) {
                            return $value;
                        })->first();

                        $document['all_fakultas'] = collect($document['authors'])->pluck('fakultas')->filter(function ($value) {
                            return $value;
                        })->unique()->implode(',');
                        $document = Document::create($document);
                        $this->info("Created $document->title");
                    }
                } catch (\Throwable $th) {
                    $this->error($th->getMessage());
                    $count = 0;
                }
            }
        }
    }

    public function getIdentifier($type, $result)
    {
        $identifier = collect($result['bibjson']['identifier'])->filter(function ($item) use ($type) {
            return $item['type'] == $type;
        })->first();

        if ($identifier) {
            return $identifier['id'];
        }

        return null;
    }
}
