@extends('layouts.app')

@section('headerext')
    <script src="{{ asset('js/pdfobject.min.js') }}"></script>    
@endsection

@section('content')
    <div class="container-lg">
        <div class="row" style="height:calc(100vh - 100px);">
            <div class="col-md-5">
                
                @if( ! empty($searchterm))
                <div class="container">
                    <form method="POST" action="/documents/search">
                        @csrf

                        <fieldset>
                                <div class="input-group mb-3">  
                                    <input type="hidden" name="st" placeholder="search term" aria-label="search term" value="{{ $searchterm }}"
                                            aria-describedby="button-addon" class="form-control" />
                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-outline-secondary" type="submit" id="button-addon">&lt;&lt; Back</button>
                                    </div>
                                </div>
                        </fieldset>
                    </form>
                </div>
                @endif 
                
                <div class="container">
                    <form method="POST" action="/documents/{{ $document->id }}/edit">
                        @csrf
                        
                        <h5 class="mb-1">{{ $document->filename }}</h5>
                        <section>
                            <small style="display:block; float: left">Created: {{ $document->created_at }}</small>
                            <small style="display:block; float: right" class="document-status">Status: @include('documents.status-badge')</small>
                        </section>
                        <div style="clear:right;" class="form-group mt-5">
                            <label for="documentContent" class="">Contents</label>
                            <textarea class="form-control" id="documentContent" name="content" rows="20">{{ $document->content }}</textarea>
                        </div>
                        <div>
                            <fieldset>
                                <div class="input-group-append">  
                                    <button class="btn btn-sm btn-outline-secondary" id="button-save" type="submit">Save</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="container">
                    <form method="POST" action="/documents/{{ $document->id }}/delete">
                        @csrf
                        
                        <div>
                            <fieldset>
                                <div class="input-group-append">  
                                    <button class="btn btn-sm btn-outline-secondary" id="button-delete" type="submit">Delete</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>

            </div>
            
            <div id="pdf" class="col-md-7">
                <!-- placeholder for PDF Viewer that is inserted -->
                <!-- 
                    document located at path: {{$document->path}} 
                -->
                @if( empty($document->path))
                <div class="text-center" style="padding: 25% 15%;">
                    <div class="alert alert-secondary" role="alert">
                        <p><strong>PDF file not found.</strong> Want to upload one?</p>
                        <form action="/documents/{{ $document->id }}/upload" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" />
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Upload</button>
                        </form>
                    </div>
                    <!-- TODO: incorporate Dropzone JS library (see below) -->
                </div>
                @endif
            </div>

        </div>
        
    </div>
    
@endsection

@section('pageend')
    @if( ! empty($document->path))
    <script>
        //insert the PDF viewer in container with CSS selector "#pdf"
        var options = {
            pdfOpenParams: { view: 'Fit', search: '{{ $searchterm }}' }
        };

        PDFObject.embed("{{ '/files/' . $document->path }}", "#pdf", options);
    </script>
    @else
    <!-- 
        <script>
        var drop = new Dropzone('#file', {
            createImageThumbnails: false,
            addRemoveLinks: true,
            clickable: true,
            url: "/documents/{{ $document->id }}/upload",
            headers: {
                'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
            }
        });
        </script>
    -->
    @endif
@endsection