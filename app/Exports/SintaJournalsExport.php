<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SintaJournalsExport implements FromArray, WithHeadings
{
    use Exportable;

    public $journals;

    public function __construct(array $journals = []) {
        $this->journals = $journals;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        return $this->journals;
    }

    public function headings(): array
    {
        return array_keys($this->journals[0]);
    }
}
