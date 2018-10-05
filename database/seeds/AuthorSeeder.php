<?php

use App\Author;
use App\Document;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = Document::get()->pluck('authors')->flatten(1)->unique('auth_id')->values()->toArray();

        Author::insert($authors);
    }
}
