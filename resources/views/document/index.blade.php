@extends('layouts.app')

@section('title', 'Document search')

@section('content')
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-8">
	            <h1>Documents Search</h1>
	        </div>
	    </div>
	    <div class="row py-4 justify-content-center">
	    	<div class="col-md-8">
	    		<form class="card card-body" action="{{ route('document.search') }}">
					<document-search-form></document-search-form>
				</form>
	    	</div>
	    </div>
	</div>
@endsection