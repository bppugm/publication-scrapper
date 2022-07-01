<?php

namespace App\Console\Commands;

use App\AuthorDocument;
use App\Document;
use App\SimasterAuthor;
use Illuminate\Console\Command;
use App\Imports\WosDocumentImport;
use App\Imports\SimasterAuthorImport;

class ImportWosDocumentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wos_document:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import WOS from wos.xls';

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
        if (SimasterAuthor::count() == 0) {
            $this->call('simaster_author:import');
        }

        $import = new WosDocumentImport;
        Document::truncate();
        $import->withOutput($this->output)->import(base_path('wos.xls'));

        $documents = Document::where('first_nip', null)->get();
        AuthorDocument::truncate();
        foreach ($documents as $document) {
            foreach ($document->names as $name) {
                $name['ut_unique_wos_id'] = $document->ut_unique_wos_id;
                AuthorDocument::create($name);
            }
        }

        $count = AuthorDocument::count();

        if ($count) {
            foreach (AuthorDocument::get() as $authorDoc) {
                $query = SimasterAuthor::whereRaw([
                    '$text' => [
                        '$search' => $authorDoc->fullname
                    ]
                ]);
                $query->getQuery()->projections = ['score' => ['$meta' => 'textScore']];
                $author = $query->orderBy('score', ['$meta' => 'textScore'])->get();

                if ($author->count()) {
                    $authorDoc->searched_fullname = $author->first()->nama;
                    $authorDoc->searched_nip = (string)$author->first()->nipnika_simaster;
                    $authorDoc->searched_nidn = (string)$author->first()->nomor_registrasi;
                    $authorDoc->searched_score = $author->first()->score;
                    $authorDoc->total_count = $author->count();
                    $authorDoc->save();
                }
            }
            $this->call('author_document:export');
            $this->info("$count unidentified authors has been exported.");
        } else {
            $this->info("No unidentified authors found. Documents are ready to be exported.");
        }
    }
}
