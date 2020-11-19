<?php

namespace App\Console\Commands;

use App\Document;
use Illuminate\Console\Command;

class MaDocumentsImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_document:import';

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
        Document::truncate();

        $json = file_get_contents(base_path('ma.json'));
        $documents = collect(json_decode($json, true));

        $documents->each(function ($document)
        {
            foreach ($document['AA'] as $key => $author) {
                if ($author['AfId'] == 165230279) {
                    Document::create([
                        'title' => $document['Ti'],
                        'author_name' => $author['AuN'],
                        'author_index' => $key + 1,
                        'author_id' => $author['AuId'],
                    ]);
                }
            }
        });
        
    }
}
