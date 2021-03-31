<?php

namespace App\Http\Livewire\Documents;

use App\Events\DocumentEvents;
use App\Http\Livewire\Common\RedirectAction;
use App\Http\Livewire\Documents\Actions\DeleteAction;
use App\Http\Livewire\Documents\Filters\CreatedAfterFilter;
use App\Http\Livewire\Documents\Filters\CreatedBeforeFilter;
use App\Http\Livewire\Documents\Filters\DocumentDateAfterFilter;
use App\Http\Livewire\Documents\Filters\DocumentDateBeforeFilter;
use App\Http\Livewire\Documents\Filters\StatusFilter;
use App\Http\Livewire\Documents\Filters\UpdatedAfterFilter;
use App\Http\Livewire\Documents\Filters\UpdatedBeforeFilter;
use App\Models\Document;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Facades\Header;
use LaravelViews\Facades\UI;
use LaravelViews\Views\TableView;

class SearchResultTable extends TableView {

    protected $listeners = [DocumentEvents::CREATED => "render"];

    public $searchBy = ['filename', 'content', 'tags.name'];

    protected $paginate = 15;

    /**
     * Sets a initial query with the data to fill the table
     *
     * @return Builder Eloquent query
     */
    public function repository(): Builder
    {
        return Document::query()->orderBy('updated_at', 'desc');
    }

    /**
     * registers the filters for this table view
     * 
     * @return Array the array with the filter instances
     */
    protected function filters() {
        return [
            new StatusFilter,
            new DocumentDateAfterFilter,
            new DocumentDateBeforeFilter,
            new CreatedAfterFilter,
            new CreatedBeforeFilter,
            new UpdatedAfterFilter,
            new UpdatedBeforeFilter,
        ];
    }


    protected function actionsByRow() {
        return [
            new RedirectAction('document.show', ['mode' => 'edit'], 'Edit', 'edit'),
            new DeleteAction,
        ];
    }

    /**
     * Sets the headers of the table as you want to be displayed
     *
     * @return array<string> Array of headers
     */
    public function headers(): Array {
        return [
            '',
            Header::title('File name')->sortBy('filename'), 
            Header::title('Status')->sortBy('status'), 
            Header::title('Created')->sortBy('created_at'), 
            Header::title('Updated')->sortBy('updated_at')
        ];
    }

    /**
     * Sets the data to every cell of a single row
     *
     * @param $model Current model for each row
     */
    public function row($model): Array
    {
        return [
            UI::icon('file-text', 'default', 'text-gray-600 h-4 w-4'),
            UI::link($model->filename, route('document.show', ['id' => $model->id])),
            DocumentHelper::getStatusBadge($model->status), 
            $model->created_at, 
            $model->updated_at
        ];
    }
}
