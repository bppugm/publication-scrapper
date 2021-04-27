<?php

namespace App\Exports;

use App\Document;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function headings(): array
    {
        $document = Document::first()->toArray();

        return array_keys($document);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Document::all();
    }
}
