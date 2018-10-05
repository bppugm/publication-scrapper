<?php

namespace App\Traits;

/**
 * Document Trait
 */
trait DocumentTrait
{
	public function documentMetrics($documents)
	{
	    $result['documents_count'] = $documents->count();

	    $indexes = ["Q1", "Q2", "Q3", "Q4"];
	    foreach ($indexes as $key => $value) {
	        $result['h_index'][$value] = $documents->where('scimago.h_index', $value)->count();
	    }

	    $years = ['2008', '2009', '2010', '2011', '2012', '2013', '2014', '2015', '2016', '2017', '2018'];
	    foreach ($years as $year) {
	        $count = $documents->filter(function ($item) use ($year)
	        {
	            return substr($item['published_at'], 0, 4) == $year;
	        })->count();
	        if ($count > 0) {
	            $result['year'][$year] = $count;
	        }
	    }

	    return $result;
	}	
}