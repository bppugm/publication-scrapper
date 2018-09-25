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

    public function filter($request)
    {
    	$model = $this;
    	
        if ($request->filled('h_index')) {
            $model = $model->where('scimago.h_index', $request->h_index);
        }

        if ($request->filled('author_id')) {
            $model = $model->where('authors.auth_id', $request->author_id);
        }

        if ($request->filled('year')) {
            $model = $model->where('published_at', 'like', "%$request->year%");
        }

        return $model;
    }    
}
