<?php

namespace App\Console\Commands;

use App\Imports\ScimagoImport;
use App\Scimago;
use Illuminate\Console\Command;

class ImportScimagoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scimago:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Scimago';

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
        Scimago::truncate();
        $import = new ScimagoImport;
        $import->withOutput($this->output)->import(base_path('scimago.csv'));
    }
}
