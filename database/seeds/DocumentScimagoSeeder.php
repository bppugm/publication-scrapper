<?php

use App\Document;
use App\Scimago;
use Illuminate\Database\Seeder;

class DocumentScimagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = Document::all();

        foreach ($documents as $document) {
        	$scimago = Scimago::where('source_id', intval($document['source_id']))->get()->first();

        	if (!empty($scimago)) {
        		$scimago = $scimago->toArray();
        	} else {
        		$scimago = null;
        	}
        	
        	$document->scimago = $scimago;
        	$document->save();
        }
    }
}
