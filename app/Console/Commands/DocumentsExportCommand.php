<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exports\DocumentsExport;

class DocumentsExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Scopus Document';

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
        $date = now()->format('dmYHis');
        $export = new DocumentsExport;
        $export->store("documents_{$date}.xlsx", 'local');
    }
}
