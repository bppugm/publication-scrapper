@extends('layouts.app')	

@section('content')
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-md-8">
	            <h1>Documents Search</h1>
	        </div>
	    </div>
	    <div class="row py-4">
	    	<div class="col-md-2">
	    		<div class="card card-body">
	    			<author-filter-base
	    			:request="{{ json_encode(request()->all()) }}"
	    			:metric="{{ json_encode($metric) }}"
	    			:query="{{ json_encode($data) }}"
	    			></author-filter-base>
		    		<a href="{{ route('document.search', $data) }}" class="btn btn-warning mt-3"><i class="fa fa-redo"></i> Reset</a>
	    		</div>
	    	</div>
	    	<div class="col-md-10">
	            <div class="card">
	            	<div class="card-header">
	            		{{ $metric['documents_count'] }} Document results
	            	</div>
	                <div class="card-body">
	                	@foreach ($documents as $document)
                		 	<document-list-item
                		 	scopus-id="{{ $document->identifier }}"
                		 	doi="{{ $document->doi }}"
                		  	title="{{ $document->title }}"
                		  	cover-date="{{ $document->published_at }}"
                		  	h-index="{{ $document->scimago['h_index'] }}"
                		  	sub-type="{{ $document->subtype_description }}"
                		  	:authors="{{ json_encode($document->authors) }}"
                		  	publication-name="{{ $document->publication_name }}"
                		  	></document-list-item>
                		  	@if (!$loop->last)
                		  		<hr>
                		  	@endif
	                	@endforeach
            		</div>
	                <div class="card-footer">
	                	{{ $documents->appends(request()->except('page'))->links() }}
	                </div>
	            </div>
	    	</div>
	    </div>
	</div>
@endsection