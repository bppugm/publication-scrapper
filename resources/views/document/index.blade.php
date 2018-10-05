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
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label class="form-label">Title or Keywords</label>
								<input required type="text" name="keyword" class="form-control">
								<small class="form-text text-muted">Required</small>
							</div>
						</div>
						<div class="col-12 text-right">
							<button type="submit" class="btn btn-success"><i class="fa fa-search fa-fw"></i> Search</button>
						</div>
					</div>
				</form>
	    	</div>
	    </div>
	</div>
@endsection