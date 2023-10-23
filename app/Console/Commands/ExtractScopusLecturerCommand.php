<?php

namespace App\Console\Commands;

use App\AuthorDocument;
use App\Document;
use Illuminate\Console\Command;

class ExtractScopusLecturerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scopus_lecturer:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export lecturer from scopus documents';

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
        $this->info('removing old data...');
        AuthorDocument::truncate();

        $this->info('indentify authors...');
        Document::all()->each(function (Document $document) {
            foreach ($document->authors as $author) {
                $pivot['index'] = $author['index'];
                $pivot['authorname'] = $author['authorname'];
                $pivot['authid'] = $author['authid'];
                $pivot['faculty'] = $author['faculty'];
                $pivot['nidn'] = $author['nidn'];
                $pivot['nip'] = $author['nip'];
                $pivot['title'] = $document->title;
                $pivot['doi'] = optional($document)->doi;
                $pivot['year'] = $document->year;
                $pivot['type'] = $document->type;
                if (optional($author)['nip']) {
                    AuthorDocument::create($pivot);
                }
            }
        });

        $this->info('exporting pivot...');
        $this->call('author_document:export');
    }
}
