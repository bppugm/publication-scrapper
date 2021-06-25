<?php

namespace App\Exports;

use App\Document;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class DocumentsExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    use Exportable;

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING2);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

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
