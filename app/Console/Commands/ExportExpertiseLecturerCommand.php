<?php

namespace App\Console\Commands;

use App\Document;
use App\Exports\ExpertiseLecturerExport;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ExportExpertiseLecturerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expertise_lecturer:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export expertise';

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
        
        $export = new ExpertiseLecturerExport;
        $export->store("expertise_lecturer_$date.xlsx", 'local');
    }
}
