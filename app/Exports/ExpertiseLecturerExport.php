<?php

namespace App\Exports;

use App\Document;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class ExpertiseLecturerExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
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
        $document = $this->collection()->first();

        return array_keys($document);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        /** @var Collection */
        $documents = Document::get();

        $collections = collect();

        $documents = $documents->each(function ($item) use ($collections) {
            foreach ($item->field as $field) {
                $collections->push(
                    [
                        'nama_dosen' => $item->nama_dosen,
                        'email' => $item->alamat_email,
                        'unit_kerja' => $item->unit_kerja,
                        'nip' => $item->nip,
                        'nidn' => $item->nidn,
                        'field' => $field,
                    ]
                );
            }
        });
        dump($collections->toArray());
        return $collections;
    }
}
