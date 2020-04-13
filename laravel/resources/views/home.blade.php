@extends('layouts.app')

@section('content')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <form method="POST" action="/documents/search">
                    @csrf

                    <fieldset>
                        <div class="card-header">
                            <legend>Search Documents</legend>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                               <input type="text" class="form-control" name="st" placeholder="search term" aria-label="search term" 
                                    aria-describedby="button-addon" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">Search</button>
                                </div>
                            </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection