<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Clients\MicrosoftAcademicClient;
use App\Formatters\CsvFormatter;
use League\Csv\Writer;
use App\Mail\MicrosoftAcademicExport;
use Illuminate\Support\Facades\Cache;

class MicrosoftAcademicScrapper implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $year;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email = null, $year = null)
    {
        $this->email = $email;
        $this->year = $year;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MicrosoftAcademicClient $client)
    {    
        $path = Cache::remember("ma-".$this->year, 60*24, function () use ($client)
        {
            $filePath = storage_path('app/ma').now()->toDateString()."-".$this->year.".csv";
            $writer = Writer::createFromPath($filePath,'w+');
            $writer->insertOne(['id', 'Title', 'year', 'authors', 'DOI']);
            
            $formatter = new CsvFormatter($writer);

            $offset = 0;
            $count = 1;
            
            while ($count != 0) {
                $count = $client->evaluate(['offset' => $offset], $this->year, $formatter);
                $offset = $offset+100;
            }

            return $filePath;
        });

        $mail = new MicrosoftAcademicExport($path);

        \Mail::to($this->email)->send($mail);
    }
}
