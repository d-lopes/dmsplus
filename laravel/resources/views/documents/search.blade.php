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
                               <input type="text" name="st" placeholder="search term" aria-label="search term" value="{{ $searchterm }}"
                                    aria-describedby="button-addon" class="form-control" />
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card-body">
                <?php echo $documents->render(); ?>
                <div class="list-group">

                    @foreach ($documents as $document)
                        @include('documents.searchresult')
                    @endforeach

                </div>
                <?php echo $documents->render(); ?>
            </div>

        </div>
    </div>
</div>

@endsection