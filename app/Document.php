<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Document extends Model
{
    protected $collection = 'documents';
    protected $connection = 'mongodb';
    protected $guarded = ['id'];
    protected $casts = [
    	'source_id' => 'integer',
    	'prism:coverDate' => 'date',
    ];    
}
