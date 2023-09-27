<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExpertisePerSheetExport implements FromCollection, WithTitle, WithHeadings
{
    protected $title;
    protected $expertise;

    public function __construct($expertise = null, $title = null) {
        $this->expertise = $expertise;
        $this->title = $title;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $collection = [];
        foreach ($this->expertise as $key => $value) {
            $collection[] = [$key, count($value), implode("; ", $value)];
        }

        return collect($collection);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return [
            'FIELD',
            'COUNT',
            'DOSEN'
        ];
    }
}
