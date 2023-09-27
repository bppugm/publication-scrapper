<?php

namespace App\Imports;

use App\Document;
use App\SimasterAuthor;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;


class SimasterDocumentImport implements OnEachRow, WithHeadingRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $names = explode("; ", $row['penulis']);
        foreach ($names as $key => $value) {
            $row['names'][$key]['fullname'] = $value;
            $query = SimasterAuthor::whereRaw([
                '$text' => [
                    '$search' => $value
                ]
            ]);
            $query->getQuery()->projections = ['score' => ['$meta' => 'textScore']];
            $search = $query->orderBy('score', ['$meta' => 'textScore'])->get();
            if ($search->count()) {
                $search = $search->first();
                if ($search->score >= 1) {
                    $row['names'][$key]['nip'] = $search->nipnika_simaster;
                    $row['names'][$key]['nidn'] = $search->nomor_registrasi;
                }
            }

        }

        $row['all_nip'] = collect($row['names'])->pluck('nip')->filter(function ($value) {
            return $value;
        })->implode(",");
        $row['first_nip'] = collect($row['names'])->pluck('nip')->filter(function ($value) {
            return $value;
        })->first();

        $row['all_nidn'] = collect($row['names'])->pluck('nidn')->filter(function ($value) {
            return $value;
        })->implode(",");
        $row['first_nidn'] = collect($row['names'])->pluck('nidn')->filter(function ($value) {
            return $value;
        })->first();
        return Document::create($row);
    }
}
