<?php

namespace App\Console\Commands;

use App\Author;
use App\Imports\ScopusAuthorImport;
use Illuminate\Console\Command;

class ImportAuthorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author:import {filename=authors.csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Authors';

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
        Author::truncate();

        $import = new ScopusAuthorImport;
        $import->withOutput($this->output)->import(base_path($this->argument('filename')));
    }
}
