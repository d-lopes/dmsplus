@extends('layouts.app')

@section('headerext')
    <script src="{{ asset('js/pdfobject.min.js') }}"></script>    
@endsection

@section('content')
    <div class="container-lg">
        <div class="row" style="height:calc(100vh - 100px);">
        <div class="col-md-5">
            
            <div class="container">
                <form method="POST" action="/documents/search">
                    @csrf

                    <fieldset>
                            <div class="input-group mb-3">  
                                <input type="hidden" name="st" placeholder="search term" aria-label="search term" value="{{ $searchterm }}"
                                        aria-describedby="button-addon" class="form-control" />
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon">&lt;&lt; Back</button>
                                </div>
                            </div>
                    </fieldset>
                </form>
            </div>
            
            <div class="container">
                @include('documents.searchresult')
            </div>
        </div>
            <div id="pdf" class="col-md-7">
                <!-- PDF Viewer to be inserted -->
            </div>
        </div>
        
    </div>
    
@endsection

@section('pageend')
    <!-- insert just before the closing body tag </body> -->
    <script>
        //Be sure your document contains an element with the CSS selector "pdf-object"
        var options = {
            pdfOpenParams: { view: 'Fit', search: '{{ $searchterm }}' }
        };

        PDFObject.embed("{{ '/files/' . $document->path }}", "#pdf", options);
    </script>
@endsection