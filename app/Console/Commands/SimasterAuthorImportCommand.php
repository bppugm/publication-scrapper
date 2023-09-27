<?php

namespace App\Console\Commands;

use App\SimasterAuthor;
use Illuminate\Console\Command;
use App\Imports\SimasterAuthorImport;
use Illuminate\Support\Facades\Schema;

class SimasterAuthorImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'simaster_author:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Simaster Author';

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
        $this->info('Importing Simaster author');
        SimasterAuthor::truncate();
        Schema::connection('mongodb')->table('simaster_authors', function ($collection) {
            $collection->index(['nama' => 'text']);
        });
        $import = new SimasterAuthorImport;
        $import->withOutput($this->output)->import(base_path('author_simaster.xlsx'));
        $this->info('Import simaster author finished');
        return 1;
    }
}
