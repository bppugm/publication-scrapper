<?php

namespace App\Console\Commands;

use App\AuthorDocument;
use App\Document;
use App\SimasterAuthor;
use Illuminate\Console\Command;

class ExtractScopusAuthorsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scopus_author:extract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract Scopus Author From Documents';

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
        AuthorDocument::truncate();

        Document::where('faculties', "")->chunk(100, function ($documents) {
            foreach ($documents as $key => $document) {
                $this->info("Exporting {$document->title} [{$document->year}]");
                foreach ($document->authors as $author) {
                    if (optional($author)['is_ugm'] && optional($author)['faculty'] == null) {
                        $author['document_scopus_id'] = $document->identifier;
                        $simaster = SimasterAuthor::searchText($author['authorname']);
                        $author['searched_name'] = optional($simaster)->nama;
                        $author['searched_nip'] = optional($simaster)->nipnika_simaster;
                        $author['nip'] = optional($simaster)->nipnika_simaster;
                        $author['searched_nidn'] = optional($simaster)->nomor_registrasi;
                        $author['nidn'] = optional($simaster)->nomor_registrasi;
                        $author['searched_score'] = optional($simaster)->score;
                        AuthorDocument::create($author);
                    }
                }
            }
        });
    }
}
