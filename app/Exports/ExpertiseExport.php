<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExpertiseExport implements WithMultipleSheets
{
    use Exportable;

    protected $expertises;

    public function __construct($expertises) {
        $this->expertises = $expertises;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->expertises as $key => $expertise) {
            $sheets[] = new ExpertisePerSheetExport($expertise, $key);
        }

        return $sheets;
    }
}
