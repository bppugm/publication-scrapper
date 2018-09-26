@extends('layouts.app')

@section('content')
	<div class="container-fluid">
	    <div class="row mb-3">
	        <div class="col-md-12">
	            <h3>Author Details</h3>
	        </div>
	    </div>
	    <div class="row">
	    	<div class="col-md-8">
	    		<div class="card card-body">
	    			<h3>I. W. Mustika</h3>
	    		</div>
	    	</div>
	    </div>
	    <div class="row py-4">
	    	<div class="col-md-3">
	    		<div class="card card-body">
	    			<author-filter-base
	    			:request="{{ json_encode(request()->all()) }}"
	    			:metric="{{ json_encode($metric) }}"
	    			></author-filter-base>
	    		</div>
	    	</div>
	    	<div class="col-md-9">
	    		<div class="card">
	    			<div class="card-header d-flex justify-content-between">
	    				<span>{{ $documents->count() }} Documents</span>
	    				<a href="#">Filter documents <i class="fa fa-arrow-right"></i></a>
	    			</div>
	    			<div class="card-body">
	    				@foreach ($documents as $document)
	    					<document-list-item
	    					title="{{ $document->title }}"
	    					:authors="{{ json_encode($document->authors) }}"
	    					sub-type="{{ $document->subtype_description }}"
	    					h-index="{{ $document->scimago['h_index'] }}"
	    					publication-name="{{ $document->publication_name }}"
	    					></document-list-item>
	    					@if (!$loop->last)
	    						<hr>
	    					@endif
	    				@endforeach
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</div>
@endsection
