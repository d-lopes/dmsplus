<?php

namespace App\Http\Livewire\Documents\Filters;

use App\Models\DocumentDate;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Filters\DateFilter;

class DocumentDateBeforeFilter extends DateFilter {
    
    public $title = "Document Date(s) Before";

    /**
     * Modify the current query when the filter is used
     *
     * @param Builder $query Current query
     * @param Carbon $date Carbon instance with the date selected
     * @return Builder Query modified
     */
    public function apply(Builder $query, Carbon $date, $request): Builder
    {
        $documentIds = DocumentDate::whereDate('date_value', '<', $date)->get()
            ->transform(function ($item, $key) { return $item->document_id; })
            ->all();

        return $query->whereIn('id', $documentIds);
    }
}
