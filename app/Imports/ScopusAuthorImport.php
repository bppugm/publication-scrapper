<?php

namespace App\Imports;

use App\Author;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ScopusAuthorImport implements WithHeadingRow, OnEachRow, WithProgressBar
{

    use Importable;

    /**
     * @param Row $row
     */
    public function onRow(Row $row)
    {
        $author = $row->toArray();

        $record = Author::create([
            'author_id' => "{$author['author_id']}",
            'name' => $author['name'],
            'faculty' => $author['faculty'],
            'nidn' => optional($author)['nidn'],
            'nip' => optional($author)['nip'],
            'ma_id' => optional($author)['ma_id'],
        ]);
    }
}
