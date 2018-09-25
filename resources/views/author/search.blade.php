@extends('layouts.app')

@section('content')
	<author-search-base
	last_name="{{ $request->last_name }}"
	first_name="{{ $request->first_name }}"></author-search-base>
@endsection
