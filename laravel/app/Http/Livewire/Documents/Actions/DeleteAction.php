<?php

namespace App\Http\Livewire\Documents\Actions;

use Illuminate\Support\Facades\Storage;
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

        $document = $model;
        $filename = $document->filename;

        // delete file from storage, if it exists
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            Storage::disk('documents')->delete($document->path);
        }

        // make sure the file is really gone
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            throw new RuntimeException("");
            $this->error( __('File at ' . $document->path . ' could not be deleted!') );
        } else {
            // delete model from DB
            $document->delete();
            $this->success( __('Document ' . $filename . ' was successfully deleted') );
        }

    }
}
