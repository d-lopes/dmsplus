<?php

namespace App\Http\Livewire\Documents\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Filters\DateFilter;

class UpdatedBeforeFilter extends DateFilter {
    
    public $title = "Updated Before";

    /**
     * Modify the current query when the filter is used
     *
     * @param Builder $query Current query
     * @param Carbon $date Carbon instance with the date selected
     * @return Builder Query modified
     */
    public function apply(Builder $query, Carbon $date, $request): Builder
    {
        return $query->whereDate('updated_at', '<', $date);
    }
}
