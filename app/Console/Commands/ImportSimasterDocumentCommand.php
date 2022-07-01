<?php

namespace App\Console\Commands;

use App\AuthorDocument;
use App\Document;
use App\SimasterAuthor;
use Illuminate\Console\Command;
use App\Imports\WosDocumentImport;
use App\Imports\SimasterAuthorImport;
use App\Imports\SimasterDocumentImport;

class ImportSimasterDocumentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simaster_document:import {filename=simaster.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Simaster document from simaster.csv';

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
        $import = new SimasterDocumentImport;
        Document::truncate();
        $import->withOutput($this->output)->import(base_path($this->argument('filename')));
        return;
    }
}
