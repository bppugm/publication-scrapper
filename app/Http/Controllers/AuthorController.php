<?php

namespace App\Http\Controllers;

use App\Document;
use App\Traits\DocumentTrait;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    use DocumentTrait;

    public function index()
    {
    	return view('author.index');
    }

    public function search(Request $request)
    {
    	$request->validate([
    		'last_name' => 'required|string',
    		'first_name' => 'string|nullable'
    	]);

    	return view('author.search', compact('request'));
    }

    public function show(Request $request, $author)
    {
        $documents = (new Document)->filter($request)->where('authors.auth_id', $author)->get();
        
        $metric = $this->documentMetrics($documents);
        
        return view('author.show', compact('author', 'documents', 'metric'));
    }
}
