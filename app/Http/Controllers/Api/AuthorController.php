<?php

namespace App\Http\Controllers\Api;

use App\Apis\ScopusAuthorRetrieval;
use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AuthorController extends Controller
{
    public $api;

    function __construct(ScopusAuthorRetrieval $api)
    {
        $this->api = $api;
    }

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
        $result = Cache::remember('scopus_author_'.$author, 300, function () use ($author)
        {
            return $this->api->getAuthor($author);
        });

        return $result;
    }
}
