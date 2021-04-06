<?php

namespace App\Console\Commands;

use App\Exports\MaAuthorExport;
use Illuminate\Console\Command;

class MaAuthorExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_author:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $export = new MaAuthorExport;
        $export->store('ma_author.xlsx', 'local');
    }
}
