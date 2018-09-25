<?php

namespace App\Http\Controllers\Api;

use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
    	$request->validate([
    		'last_name' => 'required|string',
            'first_name' => 'string|nullable',
    	]);

    	$authors = Cache::rememberForever('authors.all', function ()
    	{
    		return Document::get()->pluck('authors')->flatten(1)->unique('auth_id');
    	});

    	$authors = $authors->filter(function ($item) use ($request)
    	{
    		$val[0] = strtolower($item['surname']) == strtolower($request->last_name);

            if ($request->filled('first_name')) {
                $val[1] = strtolower($item['given-name']) == strtolower($request->first_name);
            }
            
            return !collect($val)->contains(false); 
    	});

    	return AuthorsResource::collection($authors);
    }

    public function show($author)
    {
        $documents = Document::where('authors.auth_id', $author)->get();

        $result['documents_count'] = $documents->count();
        $indexes = ["Q1", "Q2", "Q3", "Q4"];

        foreach ($indexes as $key => $value) {
            $result['h_index'][$value] = $documents->where('scimago.h_index', $value)->count();
        }

        return $result;
    }
}
