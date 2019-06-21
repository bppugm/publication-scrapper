<?php

namespace App\Formatters;

use League\Csv\Writer;

class CsvFormatter implements FormatterInterface
{
    public $writer;
    
    public function __construct(Writer $writer) {
        $this->writer = $writer;
    }
    
    public function format(array $data)
    {
        $records = collect($data['entities'])->map(function ($item)
        {
            $item = optional($item);

            return [
                'id' => $item['Id'],
                'title' => $item['Ti'],
                'year' => $item['Y'],
                'authors' => $this->getAuthorsString($item['AA']),
                'DOI' => $item['DOI'],
            ];
        });

        $this->writer->insertAll($records);

        return count($data['entities']);
    }

    public function getAuthorsString($authors)
    {
        $collectedAuthors = collect($authors)->map(function ($item)
        {
            $item = optional($item);
            
            return $item['AuN']." (".$item['AfN'].")";
        })->toArray();

        return implode("; ", $collectedAuthors);
    }
}
