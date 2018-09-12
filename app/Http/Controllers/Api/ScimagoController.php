<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Scimago;
use Illuminate\Http\Request;

class ScimagoController extends Controller
{
    public function index()
    {
    	return Scimago::paginate(25);
    }

    public function show($id)
    {
    	$scimago = Scimago::where('source_id', intval($id))->firstOrFail();

    	return $scimago;
    }
}
