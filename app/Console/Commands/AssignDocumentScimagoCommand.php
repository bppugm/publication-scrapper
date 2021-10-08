<?php

namespace App\Console\Commands;

use App\Document;
use App\Scimago;
use Illuminate\Console\Command;

class AssignDocumentScimagoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document_scimago:identify';

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
        $documents = Document::get();

        foreach ($documents as $document) {
            $scimago = Scimago::where('source_title', $document->source_title)->first();
            $scimago = optional($scimago);

            $document->h_index = $scimago->h_index;
            $document->quartile = $scimago->quartile;
            $document->save();
        }

    }
}
