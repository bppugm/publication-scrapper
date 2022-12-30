<?php

namespace App\Imports;

use App\Document;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ExpertiseImport implements OnEachRow, WithHeadingRow, WithProgressBar
{
    use Importable;

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $author = Document::firstOrCreate([
            'nip' => $row['nip']
        ],[
            'nama_dosen' => $row['nama_dosen'],
            'nip' => $row['nip'],
            'nidn' => $row['nidn'],
            'alamat_email' => $row['alamat_email'],
            'no_telepon' => $row['no_telepon'],
            'unit_kerja' => $row['unit_kerja'],
            'division' => [],
            'group' => [],
            'field' => [],
        ]);

        $expertises = explode("/", $row['area_of_expertise']);
        if (count($expertises) > 1) {
            $division = $author->division;
            array_push($division, $expertises[0]);
            $author->division = collect($division)->unique()->toArray();

            $group = $author->group;
            array_push($group, $expertises[1]);
            $author->group = collect($group)->unique()->toArray();

            $field = $author->field;
            array_push($field, $expertises[2]);
            $author->field = collect($field)->unique()->toArray();
        }

        $author->save();
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
