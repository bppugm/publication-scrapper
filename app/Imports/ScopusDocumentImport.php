<?php

namespace App\Imports;

use App\Author;
use App\Document;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ScopusDocumentImport implements OnEachRow, WithHeadingRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $document = optional($row);
        $authors = [];
        foreach (explode(";", $row['authors_id']) as $key => $id) {
            $author = Author::where('author_id', $id)->first();

            if ($author) {
                $authors[] = [
                    'index' => $key + 1,
                    'authorname' => $author->name,
                    'authid' => $author->author_id,
                    'faculty' => $author->faculty,
                    'nidn' => $author->nidn,
                    'nip' => $author->nip,
                ];
            }

            $faculties = collect($authors)->map(function ($item) {
                return optional($item)['faculty'];
            })->unique()
                ->filter(function ($item) {
                    return $item != null;
                })->implode(',');

            $selectedAuthor = collect($authors)->filter(function ($item) {
                return optional($item)['nip'];
            })->first();

            $record = Document::firstOrCreate(
                [
                    'eid' => $document['eid'],
                ],
                [
                    'year' => $document['year'],
                    'document_type' => $document['document_type'],
                    'doi' => $document['doi'],
                    'title' => $document['title'],
                    'source_title' => $document['source_title'],
                    'issn' => $document['issn'] ? implode("-", str_split($document['issn'], 4)) : '',
                    'vol' => $document['volume'],
                    'issue' => $document['issue'],
                    'page_start' => $document['page_start'],
                    'page_end' => $document['page_start'],
                    'authors' => $authors,
                    'faculties' => $faculties,
                    'selected_author' => optional($selectedAuthor)['authorname'],
                    'selected_nip' => optional($selectedAuthor)['nip'] ? $selectedAuthor['nip'] : '',
                    'selected_nidn' => optional($selectedAuthor)['nidn']
                ]
            );

        }
    }
}
