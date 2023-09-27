<?php

namespace App\Console\Commands;

use App\Document;
use App\SimasterAuthor;
use App\Clients\OpenAlexClient;
use Illuminate\Console\Command;
use App\Exports\OpenAlexDocumentsExport;

class FetchOpenAlexDocumentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oa_documents:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch UGM Open Alex documents';

    public $ugm_id = "I165230279";

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
        Document::truncate();
        $client = new OpenAlexClient;

        $year = now()->year;

        $query = [
            'filter' => "institutions.id:{$this->ugm_id},publication_year:{$year}",
            'per_page' => 100,
            'page' => 1
        ];

        $works = [];
        $count = true;

        while ($count) {
            $response = $client->works($query);
            $count = count($response['results']);
            foreach ($response['results'] as $key => $value) {
                $data = [
                    'openalex_id' => $value['id'],
                    'doi' => $value['doi'],
                    'title' => $value['title'],
                    'publication_date' => $value['publication_date'],
                    'language' => $value['language'],
                    'source_title' => $value['primary_location']['source'] ? $value['primary_location']['source']['display_name'] : null,
                    'volume' => $value['biblio']['volume'],
                    'issue' => $value['biblio']['issue'],
                    'first_page' => $value['biblio']['first_page'],
                    'last_page' => $value['biblio']['last_page'],
                    'issn' => is_array(optional($value['primary_location']['source'])['issn']) ? implode(", ", $value['primary_location']['source']['issn']) : null,
                    'type' => $value['type'],
                    'is_retracted' => $value['is_retracted'],
                    'raw_authors' => $value['authorships'],
                    'sdgs' => $value['sustainable_development_goals'],
                ];

                $identifiedAuthors = [];
                foreach ($value['authorships'] as $authorKey => $author) {
                    $institutions = collect($author['institutions']);
                    $isUgm = $institutions->where('id', "https://openalex.org/{$this->ugm_id}")->count();
                    if ($isUgm) {
                        $author['is_ugm'] = true;
                        $author['aff_counts'] = count($author['institutions']);

                        $simaster = SimasterAuthor::searchText($author['author']['display_name']);
                        $author['searched_name'] = optional($simaster)->nama;
                        $author['searched_nip'] = optional($simaster)->nipnika_simaster;
                        $author['nip'] = optional($simaster)->nipnika_simaster;
                        $author['searched_nidn'] = optional($simaster)->nomor_registrasi;
                        $author['nidn'] = optional($simaster)->nomor_registrasi;
                        $author['searched_score'] = optional($simaster)->score;   
                        $identifiedAuthors []= $author;
                    }
                }
                $data['identified_authors'] = $identifiedAuthors;

                $correspondingAuthors = collect($identifiedAuthors)->where('is_corresponding', true);
                $data['ugm_corresponding_authors_count'] = $correspondingAuthors->count();
                $data['ca_name'] = optional($correspondingAuthors->first())['searched_name'];
                $data['ca_nip'] = optional($correspondingAuthors->first())['nip'];
                $data['ca_score'] = optional($correspondingAuthors->first())['searched_score'];

                $selectedAuthor = collect($identifiedAuthors)->where('nip', '!=', null)->first();
                $data['selected_author_name'] = optional($selectedAuthor)['searched_name'];
                $data['selected_author_nip'] = optional($selectedAuthor)['nip'];
                $data['selected_author_score'] = optional($selectedAuthor)['searched_score'];
                $data['selected_author_position'] = optional($selectedAuthor)['author_position'];
                
                $document = Document::create($data);
                $this->info("Created document {$document->title}");
            }

            $query['page'] += 1;
        }
    }
}
