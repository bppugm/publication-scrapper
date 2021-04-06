<?php

namespace App\Imports;

use App\AuthorDocument;
use App\Document;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ScopusDocumentImport implements WithHeadingRow, OnEachRow, WithProgressBar
{
    use Importable;

    /**
     * @param Row $row
     */
    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $authors = collect(explode(";", $row['authors_id']))->filter(function ($value, $key)
        {
            return $value;
        });

        foreach ($authors as $key => $value) {
            $record = AuthorDocument::create([
                'author_id' => $value,
                'author' => $key + 1,
                'title' => $row['title']
            ]);
        }
        
    }
}
