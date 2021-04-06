<?php

namespace App\Console\Commands;

use App\Exports\AuthorDocumentExport;
use Illuminate\Console\Command;

class AuthorDocumentExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author_document:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export author document pivot sheets';

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
        $export = new AuthorDocumentExport;
        $export->store("pivot_{$date}.xlsx", 'local');
    }
}
