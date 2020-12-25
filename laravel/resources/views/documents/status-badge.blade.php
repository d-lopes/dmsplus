@if ($document->status == 'published') 
<span class="badge badge-pill badge-success">{{ $document->status }}</span>
@elseif ($document->status == 'incomplete')
<span class="badge badge-pill badge-danger">{{ $document->status }}</span>
@elseif ($document->status == 'pending')
<span class="badge badge-pill badge-warning">{{ $document->status }}</span>
@else
<span class="badge badge-pill badge-primary">{{ $document->status }}</span>
@endif