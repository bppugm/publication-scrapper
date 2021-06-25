<?php

namespace App\Exports;

use App\Author;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;

class AuthorExport extends StringValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    use Exportable;

    public function headings(): array
    {
        return array_keys(
            Author::first()->toArray()
        );
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Author::get();
    }
}
