<?php

namespace App\Console\Commands;

use App\Author;
use App\Clients\MicrosoftAcademicClient;
use Illuminate\Console\Command;
use Normalizer;

class IdentifyMaIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_id:identify';

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
        $authors = Author::whereNotNull('nidn')->whereNull('ma_id')->get();

        $client = new MicrosoftAcademicClient;
        $query = [
          'offset' => 0,
          'count' => 1,
        ];

        foreach ($authors as $author) {
            $name = str_replace(array(
                '\'', '"', ',', ';', '<', '>', '.'
            ), '', $author->name);
            $name = strtolower($name);
            $query['expr'] = "AND(Composite(AA.AfId=165230279),Composite(AA.AuN='{$name}'))";
            $this->info("Searching author: $name");
            $response = $client->evaluate($query);

            if ($document = optional($response['entities'])[0]) {
                $maAuthor = collect($document['AA'])->filter(function ($item)
                {
                    return optional($item)['AfId'] == 165230279;
                })->firstWhere('AuN', $name);

                if ($maAuthor) {
                    $this->info("MA Author found: {$maAuthor['AuN']}. Updating ma_id...");
                    $author->update(['ma_id' => $maAuthor['AuId']]);
                    $this->info("Author ma_id {$maAuthor['AuId']} updated.");
                }

            }

        }

    }
}
