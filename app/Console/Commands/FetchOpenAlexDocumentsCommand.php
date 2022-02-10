<?php

namespace App\Console\Commands;

use App\Clients\OpenAlexClient;
use App\Exports\OpenAlexDocumentsExport;
use Illuminate\Console\Command;

class FetchOpenAlexDocumentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'oa_documents:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch UGM Open Alex documents';

    public $ugm_id = "I165230279";

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
        $client = new OpenAlexClient;

        $year = now()->year;

        $query = [
            'filter' => "authorships.institutions.id:{$this->ugm_id},publication_year:{$year}",
            'per-page' => 200,
            'page' => 1
        ];

        $count = 1;
        $works = [];

        $response = $client->works($query);
        foreach ($response['results'] as $key => $value) {
            $works[] = $value;
        }

        $export = new OpenAlexDocumentsExport($works);

        $date = now()->format('dmYHis');
        $export->store("oa_documents_{$date}.xlsx", 'local');

        return 1;
    }
}
