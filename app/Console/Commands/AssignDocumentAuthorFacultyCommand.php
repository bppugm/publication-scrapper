<?php

namespace App\Console\Commands;

use App\Author;
use App\Document;
use Illuminate\Console\Command;

class AssignDocumentAuthorFacultyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'document:identify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Identify Document faculty';

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
        $documents = Document::where('authors.faculty', null)->get();

        foreach ($documents as $document) {
            foreach ($document->authors as $key => $author) {
                if (optional($author)['faculty'] == null && optional($author)['is_ugm'] == true) {
                    $faculty = optional(Author::where('author_id', $author['authid'])->first())->faculty;
                    $document->update(["authors.$key.faculty" => $faculty]);
                    $this->info("Updating author_id {$author['authid']} on document {$document->title}");
                }
            }
            $faculties = collect($document->authors)->pluck('faculty')->filter(function ($item) {
                return $item != null;
            })->unique()->implode(',');
            $document->update(['faculties' => $faculties]);
        }
    }
}