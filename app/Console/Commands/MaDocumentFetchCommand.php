<?php

namespace App\Console\Commands;

use App\Author;
use App\AuthorDocument;
use App\Document;
use Illuminate\Console\Command;
use App\Clients\MicrosoftAcademicClient;

class MaDocumentFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_document:fetch {year?} {--biodiversity}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Microsoft Academic Documents';

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
        AuthorDocument::truncate();

        $client = new MicrosoftAcademicClient;

        $offset = 0;
        $count = 1;

        $year = $this->argument('year') ?: now()->year;

        while ($count != 0) {
            $query = ['offset' => $offset];

            if ($this->option('biodiversity')) {
                $query['expr'] = "AND(Composite(AA.AfN=='gadjah mada university'),Y=$year,OR(Composite(F.FN='biodiversity'),Composite(F.FN='biology'),Composite(F.FN='deforestation'),Composite(F.FN='indigenous'),Composite(F.FN='forestry'),Composite(F.FN='tropics'),Composite(F.FN='agriculture'),Composite(F.FN='forest plot'),Composite(F.FN='forest change'),Composite(F.FN='forest cover'),Composite(F.FN='forest ecology'),Composite(F.FN='biochemistry'),Composite(F.FN='biotechnology'),Composite(F.FN='microbiology'),Composite(F.FN='conservation dependent species'),Composite(F.FN='oceanography'),Composite(F.FN='fishery'),Composite(F.FN='zoo'),Composite(F.FN='aquarium'),Composite(F.FN='threatened species'),Composite(F.FN='natural resource'),Composite(F.FN='cultural diversity'),Composite(F.FN='sociocultural perspective'),Composite(F.FN='plant science'),Composite(F.FN='animal science'),Composite(F.FN='earth science'),Composite(F.FN='water resources')))";
            }

            $response = $client->evaluate($query, $year);

            $documents = collect($response['entities']);

            $documents->each(function ($document, $index) use ($offset) {
                $document = optional($document);
                $journal = $document['J'] ? $document['J']['JN'] : '';

                $authors = collect($document['AA'])->map(function ($item, $index) {
                    $data = optional(Author::where('ma_id', $item['AuId'])->first());

                    if (!$data) {
                        $data = optional(Author::where('name', $item['AuN'])->first());
                    }

                    return [
                        'index' => $index+1,
                        'authorname' =>  $item['AuN'],
                        'authid' => $item['AuId'],
                        'faculty' => $data->faculty,
                        'nidn' => $data->nidn,
                        'nip' => $data->nip,
                    ];
                });

                $faculties = $authors->map(function ($item)
                {
                    return optional($item)['faculty'];
                })->unique()
                ->filter(function ($item)
                {
                    return $item != null;
                })->implode(',');

                $nidn = $authors->map(function ($item) {
                    return optional($item)['nidn'];
                })->unique()
                ->filter(function ($item) {
                    return $item != null;
                })->implode(',');

                $nip = $authors->map(function ($item) {
                    return optional($item)['nip'];
                })->unique()
                ->filter(function ($item) {
                    return $item != null;
                })->implode(',');

                $this->info('Imported document: ' . $document['Ti']);
                $document = Document::create([
                    'no' => $offset+$index+1,
                    'article_id' => $document['Id'],
                    'title' => $document['Ti'],
                    'authors' => $authors->toArray(),
                    'faculties' => $faculties,
                    'nidn' => $nidn,
                    'nip' => $nip,
                    'year' => $document['Y'],
                    'journal' => $journal,
                    'volume' => $document['V'],
                    'issue' => $document['I'],
                    'first_page' => $document['FP'],
                    'last_page' => $document['LP'],
                    'DOI' => $document['DOI'],
                    'type' => $this->getPublicationType($document['Pt']),
                    'language' => ''
                ]);

                // foreach ($document->authors as $key => $author) {
                //     if ($author['AfId'] == 165230279) {
                //         AuthorDocument::create([
                //             'title' => $document->title,
                //             'author_name' => $author['AuN'],
                //             'author_index' => $key + 1,
                //             'author_id' => $author['AuId'],
                //         ]);
                //     }
                // }
            });

            $count =  count($response['entities']);
            $offset = $offset + 100;
        }
    }

    protected function getPublicationType($type)
    {
        switch ($type) {
            case '1':
                return 'Journal Publication';
                break;

            case '2':
                return 'Patent';
                break;

            case '3':
                return 'Conference Publication';
                break;

            case '4':
                return 'Book Chapters';
                break;

            case '5':
                return 'Books';
                break;

            case '8':
                return 'Repository';
                break;

            default:
                return 'Other';
                break;
        }
    }
}
