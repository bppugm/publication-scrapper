<?php

namespace App\Console\Commands;

use App\Document;
use App\Exports\ExpertiseExport;
use Illuminate\Console\Command;

class ExportExpertiseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expertise:export';

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
        $expertise = [];
        $documents = Document::all()->groupBy('unit_kerja');

        foreach ($documents as $unit => $users) {
            $expertise[$unit] = [];
            foreach ($users as $user) {
                foreach ($user->field as $key => $value) {
                    if (!key_exists($value, $expertise[$unit])) {
                        $expertise[$unit][$value] = [];
                    }
                    array_push($expertise[$unit][$value], $user->nama_dosen);
                }
            }
        }

        $export = new ExpertiseExport($expertise);
        $export->store("expertise_$date.xlsx", 'local');
    }
}
