<?php

namespace App\Console\Commands;

use App\Author;
use App\AuthorDocument;
use App\Document;
use Illuminate\Console\Command;
use App\Clients\MicrosoftAcademicClient;

class MaDocumentFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_document:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Microsoft Academic Documents';

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
        AuthorDocument::truncate();

        $client = new MicrosoftAcademicClient;

        $offset = 0;
        $count = 1;

        while ($count != 0) {
            $response = $client->evaluate(['offset' => $offset], 2021);

            $offset = $offset + 100;
            $documents = collect($response['entities']);

            $documents->each(function ($document) {
                $document = optional($document);
                $journal = $document['J'] ? $document['J']['JN'] : '';
                $this->info('Imported document: ' . $document['Ti']);
                $document = Document::create([
                    'article_id' => $document['Id'],
                    'faculty' => '',
                    'title' => $document['Ti'],
                    'authors' => $document['AA'],
                    'year' => $document['Y'],
                    'journal' => $journal,
                    'DOI' => $document['DOI'],
                    'type' => $this->getPublicationType($document['Pt']),
                    'language' => ''
                ]);

                foreach ($document->authors as $key => $author) {
                    if ($author['AfId'] == 165230279) {
                        AuthorDocument::create([
                            'title' => $document->title,
                            'author_name' => $author['AuN'],
                            'author_index' => $key + 1,
                            'author_id' => $author['AuId'],
                        ]);
                    }
                }
            });

            $count =  count($response['entities']);
        }
    }

    protected function getPublicationType($type)
    {
        switch ($type) {
            case '1':
                return 'Journal Publication';
                break;

            case '2':
                return 'Patent';
                break;

            case '3':
                return 'Conference Publication';
                break;

            case '4':
                return 'Book Chapters';
                break;

            case '5':
                return 'Books';
                break;

            case '8':
                return 'Repository';
                break;

            default:
                return 'Other';
                break;
        }
    }
}
