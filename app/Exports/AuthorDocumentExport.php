<?php

namespace App\Exports;

use App\AuthorDocument;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuthorDocumentExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        return array_keys(
            AuthorDocument::first()->toArray()
        );
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AuthorDocument::all();
    }
}
