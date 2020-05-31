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

          <legend>Stats</legend>

          <div id="multi-item-example" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner" role="listbox">

              @foreach ($stats as $kpi)
                @include('documents.stats-carousel-item')
              @endforeach
              
            </div>

            <!-- controls -->
            <a class="carousel-control-prev" href="#multi-item-example" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#multi-item-example" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>

            <!--Indicators-->
            <ol class="carousel-indicators" style="margin-bottom: -5px">
              <li data-target="#multi-item-example" data-slide-to="0" class="active">.</li>
              <li data-target="#multi-item-example" data-slide-to="1">.</li>
              <li data-target="#multi-item-example" data-slide-to="2">.</li>
              <li data-target="#multi-item-example" data-slide-to="3">.</li> 
            </ol>

          </div>

        </div>

        <div class="col-sm-6">

            <!-- placeholder for future tiles -->
                        
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <legend>Most Recent</legend>
            
            <div class="card-body" style="clear: right">
                <div class="list-group">
                    @foreach ($latestDocuments as $document)
                        @include('documents.searchresult')
                    @endforeach
                </div>
            </div>

        </div>
        <div class="col-sm-6">
            
            <legend>To Review</legend>
            
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