<?php

namespace App\Exports;

use App\AuthorDocument;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class AuthorDocumentExport implements FromCollection
{
    use Exportable;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AuthorDocument::all();
    }
}
