<?php

namespace App\Console\Commands;

use App\Document;
use Carbon\Carbon;
use App\SimasterAuthor;
use App\Clients\EbscoClient;
use Illuminate\Console\Command;

class FetchEbscoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document_ebsco:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Ebsco Document';

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
        $client = new EbscoClient;

        $query = [
            'query' => "DT 2022 AND AF(gadjah mada)",
            'startrec' => 1,
            'numrec' => 200,
            'db' => 'a9h'
        ];

        $count = 1;

        while ($count) {
            $data = $client->search($query);
            $count = (string)$data->Hits;

            if ($count) {
                foreach ($data->SearchResults->records->rec as $record) {
                    $document = Document::create($this->mapRecord($record));
                    $this->info($document->title);
                }
            }

            $query['startrec'] += $query['numrec'];
        }
    }

    public function mapRecord($record)
    {
        $document['title'] = (string)$record->header->controlInfo->artinfo->tig->atl;
        $document['doi'] = (string)$record->header->controlInfo->artinfo->ui[1];
        $document['journal'] = (string)$record->header->controlInfo->jinfo->jtl;
        $document['issn'] = (string)$record->header->controlInfo->jinfo->issn;
        $document['year'] = (string)$record->header->controlInfo->pubinfo->dt['year'];
        $month = (string)$record->header->controlInfo->pubinfo->dt['month'];
        $day = (string)$record->header->controlInfo->pubinfo->dt['day'];
        $document['date'] = now()->setDate($document['year'], $month, $day)->toDateString();
        $document['language'] = (string)$record->header->controlInfo->language;
        $authors = $this->fetchAuthors($record->header->controlInfo->artinfo->aug->au);
        $document['authors'] = $authors;

        $firstAuthor  = collect($authors)->filter(function ($item) {
            return optional($item)['nip'];
        })->first();

        if ($firstAuthor) {
            $document['first_nip'] = $firstAuthor['nip'];
            $document['first_nidn'] = $firstAuthor['nidn'];
            $document['first_fullname'] = $firstAuthor['fullname'];
            $document['first_searched_name'] = $firstAuthor['searched_name'];
            $document['first_score'] = $firstAuthor['score'];
            $document['first_fakultas'] = $firstAuthor['fakultas'];

            $document['all_fakultas'] = collect($authors)->pluck('fakultas')->filter(function ($item) {
                return $item;
            })->unique()->implode(",");
        }


        return $document;
    }

    public function fetchAuthors($authorsRaw)
    {
        $authorsRaw = json_decode(json_encode($authorsRaw), 1);
        foreach ($authorsRaw as $key => $author) {
            $array = explode(", ", $author);
            $name = optional($array)[1] . " " . $array[0];
            $searched = SimasterAuthor::searchText($name);

            $authors[$key]['fullname'] = $name;

            if ($searched) {
                $authors[$key]['searched_name'] = $searched->nama;
                $authors[$key]['nip'] = $searched->nipnika_simaster;
                $authors[$key]['nidn'] = $searched->nomor_registrasi;
                $authors[$key]['score'] = $searched->score;
                $authors[$key]['fakultas'] = $searched->fakultas;
            }
        }

        return $authors;
    }
}
