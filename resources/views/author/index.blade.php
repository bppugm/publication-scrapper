@extends('layouts.app')

@section('title', 'Author search')

@section('content')
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-8">
	            <h1>Authors Search</h1>
	        </div>
	    </div>
	    <div class="row py-4 justify-content-center">
	    	<div class="col-md-8">
	    		<form class="card card-body" action="{{ route('author.search') }}">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label">Last name</label>
								<input required type="text" name="last_name" class="form-control">
								<small class="form-text text-muted">Required</small>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label">First name</label>
								<input type="text" name="first_name" class="form-control">
							</div>
						</div>
						<div class="col text-right">
							<button type="submit" class="btn btn-success"><i class="fa fa-search fa-fw"></i> Search</button>
						</div>
					</div>
				</form>
	    	</div>
	    </div>
	</div>
@endsection