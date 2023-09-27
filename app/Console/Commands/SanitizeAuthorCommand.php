<?php

namespace App\Console\Commands;

use App\Author;
use App\SimasterAuthor;
use Illuminate\Console\Command;

class SanitizeAuthorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'author:sanitize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sanitize Author';

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
        foreach (Author::get() as $author) {
            $search = SimasterAuthor::searchText($author->name);
            $author->searched_name = optional($search)->nama;
            $author->searched_nip = optional($search)->nipnika_simaster;
            $author->searched_nidn = optional($search)->nomor_registrasi;
            $author->searched_score = optional($search)->score;
            $author->is_match = $author->searched_nip == $author->nip;
            $author->save();
            $this->info("Sanitized $author->name");
        }
    }
}
