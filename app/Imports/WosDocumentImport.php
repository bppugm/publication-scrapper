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
            $row['names'][$key]['index'] = $key+1;
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

        $selectedAuthor = collect($row['names'])->filter(function ($item) {
            return optional($item)['nip'];
        })->first();

        $row['selected_author'] = optional($selectedAuthor)['fullname'];
        $row['selected_index'] = optional($selectedAuthor)['index'];
        $row['selected_nip'] = optional($selectedAuthor)['nip'];
        $row['selected_nidn'] = optional($selectedAuthor)['nidn'];
        $row['selected_fakultas'] = optional($selectedAuthor)['fakultas'];

        $row['all_nip'] = collect($row['names'])->pluck('nip')->filter(function ($value)
        {
            return $value;
        })->implode(",");

        $row['all_nidn'] = collect($row['names'])->pluck('nidn')->filter(function ($value) {
            return $value;
        })->implode(",");

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
