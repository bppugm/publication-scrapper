<?php

namespace App\Http\Controllers;

use App\Document;
use App\Traits\DocumentTrait;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use DocumentTrait;
	
    public function index(Request $request)
    {
    	return view('document.index');
    }

    public function search(Request $request)
    {
        $data = $request->validate([
            'keyword' => 'string|nullable',
        ]);

    	$document = new Document;

        $documents = $document->filter($request)->paginate(25);

        $metric = $this->documentMetrics($document->filter($request)->get());

    	return view('document.search', compact('documents', 'metric', 'data'));
    }
}
