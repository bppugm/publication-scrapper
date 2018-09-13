<?php

use App\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentHIndexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = [2017, 2016, 2015, 2014];
        $documents = Document::all();

        foreach ($documents as $document) {

        	foreach ($years as $year) {
        		$hIndex[$year] = $this->getHIndex($year, $document['source_id']); 
        	}

        	$document->h_indexes = $hIndex;
        	$document->save();        	
        }
    }

    public function getHIndex($year, $sourceId)
    {
    	$h = DB::connection('mongodb')
			->collection("scimago$year")
			->where('source_id', $sourceId)
			->first();

		if (!empty($h)) {
			return $h['h_index'];
		} else {
			return null;
		}
    }
}
