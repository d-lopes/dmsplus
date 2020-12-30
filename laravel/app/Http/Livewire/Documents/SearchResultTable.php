<?php

namespace App\Http\Livewire\Documents;

use App\Events\DocumentEvents;
use App\Http\Livewire\Documents\Actions\DeleteAction;
use App\Http\Livewire\Documents\Filters\CreatedAfterFilter;
use App\Http\Livewire\Documents\Filters\CreatedBeforeFilter;
use App\Http\Livewire\Documents\Filters\StatusFilter;
use App\Http\Livewire\Documents\Filters\UpdatedAfterFilter;
use App\Http\Livewire\Documents\Filters\UpdatedBeforeFilter;
use App\Models\Document;
use App\Models\DocumentStatus;
use Illuminate\Database\Eloquent\Builder;
use LaravelViews\Actions\RedirectAction;
use LaravelViews\Facades\Header;
use LaravelViews\Facades\UI;
use LaravelViews\Views\TableView;

class SearchResultTable extends TableView {

    protected $listeners = [DocumentEvents::CREATED => "render"];

    public $searchBy = ['filename', 'content'];

    protected $paginate = 15;

    /**
     * Sets a initial query with the data to fill the table
     *
     * @return Builder Eloquent query
     */
    public function repository(): Builder
    {
        return Document::query();
    }

    /**
     * registers the filters for this table view
     * 
     * @return Array the array with the filter instances
     */
    protected function filters() {
        return [
            new StatusFilter,
            new CreatedAfterFilter,
            new CreatedBeforeFilter,
            new UpdatedAfterFilter,
            new UpdatedBeforeFilter,
        ];
    }


    protected function actionsByRow() {
        return [
            new RedirectAction('documents.edit', 'Edit', 'edit'),
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

    private static function getStatusBadge($status): string {
        $type = '';
        switch ($status) {
            case DocumentStatus::CREATED:
                $type = 'info';
                break;
            case DocumentStatus::PENDING:
                $type = 'warning';
                break;
            case DocumentStatus::INCOMPLETE:
                $type = 'danger';
                break;
            case DocumentStatus::PUBLISHED:
                $type = 'success';
                break;
            default:
                $type = 'default';
                break;
        }

        return UI::badge(DocumentStatus::asLabel($status), $type);
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
            SearchResultTable::getStatusBadge($model->status), 
            $model->created_at, 
            $model->updated_at
        ];
    }
}
