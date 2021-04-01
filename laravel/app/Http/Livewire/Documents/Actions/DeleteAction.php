<?php

namespace App\Http\Livewire\Documents\Actions;

use App\Http\Resources\DocumentWrapper;
use LaravelViews\Actions\Action;
use LaravelViews\Actions\Confirmable;
use RuntimeException;

class DeleteAction extends Action {

    use Confirmable;
    
    public $title = "Delete";

    public $icon = "trash-2";

    /**
     * return confirmation message
     * 
     * @param String item - the item to perform the action on
     * 
     * @return String confirmation message 
     */
    public function getConfirmationMessage($item = null) {
        return __('Do you really want to delete this document? The data will bot be recoverable.');
    }

    /**
     * Execute the action when the user clicked on the button
     *
     * @param $model Model object of the list where the user has clicked
     */
    public function handle($model) {
        try {
            $wrapper = new DocumentWrapper($model);
            $wrapper->handleDeleteAction();

            $this->success( __('Document ' . $model->filename . ' was successfully deleted') );
        } catch (RuntimeException $e) {
            $this->error( __('File could not be deleted! Reason: ') . $e->getMessage());
        }
    }
}
