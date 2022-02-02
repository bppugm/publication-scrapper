<?php

namespace App\Console\Commands;

use App\Clients\SintaClient;
use App\Exports\SintaJournalsExport;
use Illuminate\Console\Command;

class FetchSintaJournalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sinta_journals:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch sinta journals';

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
        $params = [
            'page' => 1,
            'items' => 500
        ];

        $journals = [];
        $client = new SintaClient;

        $count = 1;

        while ($count) {
            try {
                $response = $client->listJournals($params);
            } catch (\Throwable $th) {
                if ($th->getCode() == 500) {
                    $client->forgetApiKey();
                    $response = $client->listJournals($params);
                } else {
                    throw new Exception($th->getMessage(), 1);
                }
            }

            $count = count($response['journals']);

            foreach ($response['journals'] as $key => $value) {
                $journals[] = $value;
            }

            $params['page']++;
        }



        $export = new SintaJournalsExport($journals);

        $date = now()->format('dmYHis');
        $export->store("sinta_journals_{$date}.xlsx", 'local');

        return 1;
    }
}
