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

    public function test()
    {
        return Document::pluck('authors')->flatten(1)->first();
    }

    public function index(Request $request)
    {
        $documents = Document::paginate(25);
        return $documents;
    	// return DocumentsResource::collection($documents);
    }

    public function show()
    {
    	$document = Document::first();
    	$scimago = Scimago::where('source_id', intval($document['source-id']))->first();

    	return $scimago->toArray();
    }
}
