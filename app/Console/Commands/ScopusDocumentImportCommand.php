<?php

namespace App\Console\Commands;

use App\Document;
use App\Imports\ScopusDocumentImport;
use Illuminate\Console\Command;

class ScopusDocumentImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scopus:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Scopus';

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
        $import = new ScopusDocumentImport;
        $import->withOutput($this->output)->import(base_path('scopus.csv'));
    }
}
