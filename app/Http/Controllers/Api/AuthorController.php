<?php

namespace App\Http\Controllers\Api;

use App\Apis\ScopusAuthorRetrieval;
use App\Author;
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

    	$author = new Author();

        $author = $author->where('surname', 'like', $request->last_name);

        if ($request->filled('first_name')) {
            $author = $author->where('given-name', 'like', $request->first_name);
        }

        $authors = $author->get();

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
