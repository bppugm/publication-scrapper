<?php

namespace App\Http\Controllers\Api;

use App\Document;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentsResource;
use App\Http\Resources\DocumentsTransformer;
use App\Scimago;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
	protected $client;

	function __construct()
	{
		$this->setClient();
	}

	public function setClient()
	{
		$this->client = new Client;
	}

    public function index(Request $request)
    {
        $document = new Document;

        if ($request->filled('h_index')) {
            $document = $document->where('scimago.h_index', $request->h_index);
        }

        $documents = $document->paginate(20);
        
    	return DocumentsResource::collection($documents);
    }

    public function show()
    {
    	$document = Document::first();
    	$scimago = Scimago::where('source_id', intval($document['source-id']))->first();

    	return $scimago->toArray();
    }
}
