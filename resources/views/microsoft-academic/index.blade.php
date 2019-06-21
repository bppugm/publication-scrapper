@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissable" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissable" role="alert">
                        Whoops! Something went wrong.
                    </div>
                @endif
                <h1>Microsoft Academic Export Data</h1>
                <p>Fill this form to get  Microsoft Academic Data sent to your email as a <code>csv</code> file.</p>
                <div class="card card-body">
                    <form action="{{ route('microsoft-academic.store') }}" method="post">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="" class="form-label">
                                    Select Year
                                </label>
                                <select name="year" id="" class="form-control">
                                    <option>2019</option>
                                    <option>2018</option>
                                    <option>2017</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">
                                    Email
                                </label>
                                <input type="email" required name="email" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success text-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection