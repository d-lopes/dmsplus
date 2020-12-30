<?php

namespace App\Http\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AttentionOverviewTable extends SearchResultTable {

    // hide search (as we do not want them to show in the 'documents that need your attention overview')
    public $searchBy = [];

    // restrict number of items shown to 5 in the 'documents that need your attention overview')
    protected $paginate = 5;

    // all documents that are not in published status and are older than 3 min
    public function repository(): Builder {        
        $threeMinutesAgo = Carbon::now()->subMinutes(3);
        
        return Document::query()
                    ->where('status', '<>', DocumentStatus::PUBLISHED) 
                    ->whereDate('created_at', '<=', $threeMinutesAgo);
    }

    // hide filters (as we do not want them to show in the 'documents that need your attention overview')
    protected function filters() {
        return [];
    }

    // hide actions (as we do not want them to show in the 'documents that need your attention overview')
    protected function actionsByRow() {
        return [];
    }

    // remove column 'updated' from headers
    public function headers(): Array {
        $headers = parent::headers();
        array_pop($headers);

        return $headers;
    }

    // remove column 'updated' from rows
    public function row($model): Array {
        $row = parent::row($model);
        array_pop($row);

        return $row;
    }

}
