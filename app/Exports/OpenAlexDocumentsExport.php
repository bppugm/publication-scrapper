<?php

namespace App\Exports;

use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class OpenAlexDocumentsExport implements FromArray, WithHeadings, WithMapping
{
    use Exportable;

    public $documents;

    public function __construct($documents = []) {
        $this->documents = $documents;
    }

    public function array(): array
    {
        return $this->documents;
    }

    public function map($row): array
    {
        return [
            $row['display_name'],
            str_replace("https://doi.org/","",$row['doi']),
            $row['publication_year']
        ];
    }

    public function headings(): array
    {
        return [
            'title',
            'doi',
            'year'
        ];
    }
}
