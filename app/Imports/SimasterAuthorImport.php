<?php

namespace App\Imports;

use App\SimasterAuthor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

class SimasterAuthorImport implements OnEachRow, WithHeadingRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $row = array_filter($row->toArray());
        SimasterAuthor::create($row);
    }
}
