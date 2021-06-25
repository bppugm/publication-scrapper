<?php

namespace App\Console\Commands;

use App\Exports\AuthorExport;
use Illuminate\Console\Command;

class ExportAuthorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all authors';

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
        $export = new AuthorExport;
        $export->store("authors_{$date}.xlsx", 'local');
    }
}
