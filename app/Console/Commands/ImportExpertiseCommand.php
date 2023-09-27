<?php

namespace App\Console\Commands;

use App\Document;
use Illuminate\Console\Command;
use App\Imports\ExpertiseImport;

class ImportExpertiseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expretise:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import UGM expertise';

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
        $import = new ExpertiseImport;
        Document::truncate();
        $import->withOutput($this->output)->import(base_path('expertise.xlsx'));
    }
}
