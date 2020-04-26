@extends('layouts.app')

@section('content')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <legend>Most Recent ({{ count($latestDocuments) }})</legend>
            
            <div class="card-body" style="clear: right">
                <div class="list-group">
                    @foreach ($latestDocuments as $document)
                        @include('documents.searchresult')
                    @endforeach
                </div>
            </div>

        </div>
        <div class="col-sm-6">
            
            <legend>To Review ({{ count($reviews) }})</legend>
            
            <div class="card-body" style="clear: right">
                <div class="list-group">
                    @foreach ($reviews as $document)
                        @include('documents.searchresult')
                    @endforeach
                </div>
            </div>
                        
        </div>
    </div>
</div>


@endsection