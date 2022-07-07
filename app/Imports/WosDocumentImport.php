<?php

namespace App\Imports;

use App\Document;
use App\SimasterAuthor;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Row;

class WosDocumentImport implements WithHeadingRow, OnEachRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $row = $row->toArray();
        $names = explode("; ", $row['author_full_names']);
        foreach ($names as $key => $value) {
            $value = $this->transform($value);
            $row['names'][$key]['fullname'] = $value;
            $author = SimasterAuthor::where('nama', 'like', $value)->first();

            if ($author) {
                $row['names'][$key]['nip'] = $author->nipnika_simaster;
                $row['names'][$key]['nidn'] = $author->nomor_registrasi;
                $row['names'][$key]['fakultas'] = $author->fakultas;
            }else {
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
                        $row['names'][$key]['fakultas'] = $search->fakultas;
                    }
                }

            }
        }

        $row['all_nip'] = collect($row['names'])->pluck('nip')->filter(function ($value)
        {
            return $value;
        })->implode(",");
        $row['first_nip'] = collect($row['names'])->pluck('nip')->filter(function ($value)
        {
            return $value;
        })->first();

        $row['all_nidn'] = collect($row['names'])->pluck('nidn')->filter(function ($value) {
            return $value;
        })->implode(",");
        $row['first_nidn'] = collect($row['names'])->pluck('nidn')->filter(function ($value) {
            return $value;
        })->first();

        $row['all_fakultas'] = collect($row['names'])->pluck('fakultas')->filter(function ($value) {
            return $value;
        })->unique()->implode(",");
        return Document::create($row);
    }

    public function transform($name)
    {
        $name = str_replace(".", "", $name);
        $name = collect(explode(", ", $name))->unique();
        if ($name->count() == 1) {
            return $name->first();
        }

        return "$name[1] $name[0]";
    }
}
