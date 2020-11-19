<?php

namespace App\Console\Commands;

use App\Author;
use Illuminate\Console\Command;

class MaAuthorImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ma_author:import';

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
        Author::truncate();

        $json = file_get_contents(base_path('ma.json'));
        $arr = collect(json_decode($json, true));
        $authors = $arr->pluck('AA')->flatten(1)->filter(function ($item)
        {
            return $item['AfId'] == 165230279;
        })->unique('AuId');
        ;

        $authors->each(function ($item)
        {
            Author::create($item);
        });

        return Author::count();
    }
}
