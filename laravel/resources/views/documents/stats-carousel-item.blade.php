@if ($kpi->type == 'total') 
<div class="carousel-item active">
@else
<div class="carousel-item">
@endif

    <div class="col-md-12" style="float:left">
     <div class="card mb-12" style="background-color: #aaa">
        <div class="card-body" style="text-align:center !important;">
          <p class="card-text">No. of Documents</p>
          <h4 class="card-title">
            @if ($kpi->type == 'total') 
              Total:
            @elseif ($kpi->type == 'published')
              Published:
            @elseif ($kpi->type == 'incomplete')
              Incomplete:
            @elseif ($kpi->type == 'new')
              New:
            @else
              Unknown:
            @endif
            {{ $kpi->value }}
          </h4>
        </div>
      </div>
    </div>

</div>