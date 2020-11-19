<?php

namespace App\Imports;

use App\Scimago;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class ScimagoImport implements WithHeadingRow, OnEachRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $scimago = [
            'Sourceid' => $data['sourceid'],
            'H index' => $data['h_index']
        ];

        Scimago::create($scimago);
    }
}
