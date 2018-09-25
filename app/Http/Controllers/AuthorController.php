<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthorController extends Controller
{
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

    public function show($author)
    {
        return view('author.show', compact('author'));
    }
}
