<a href="{{ url('/documents/' . $document->id) . '?st=' . $searchterm }}" class="list-group-item list-group-item-action">
    <div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">{{ $document->filename }}</h5>      
      @include('documents.status-badge')
    </div>
    <p class="mb-1">{{ Str::limit($document->content, 120) }}</p>
    <small class="text-right">{{ $document->created_at }}</small>
  </a>