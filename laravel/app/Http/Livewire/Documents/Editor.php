<?php

namespace App\Http\Livewire\Documents;

use App\Http\Livewire\Common\ComponentBase;
use App\Models\Document;
use App\Models\DocumentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Livewire\WithFileUploads;
use RuntimeException;

class Editor extends ComponentBase {

    use WithFileUploads;
    
    public $rules = [ 'editorTags' => 'nullable' ];

    public $document;
    public $mode = 'show';

    // form data
    public $filename;
    public $content;
    
    public $editorTags;

    public $editorFileinput;
    public $editorFileInputName;

    public $confirmationMessage;

    public function mount(Document $document, Request $request)
    {
        $this->filename = $document->filename;
        $this->content = $document->content;
        $this->editorTags = $document->simpleTags;        
        $this->mode = $request->mode;
    }

    public function confirmDelete() {
        $this->confirm( __('Do you really want to delete this document? The data will bot be recoverable.') );
    }

    public function cancelDelete() {
        $this->resetMessages();
    }

    public function delete() {
        try {
            $filename = $this->document->filename;

            DocumentHelper::handleDeleteAction($this->document);
            $this->success( __('Document ' . $filename . ' was successfully deleted') );

            redirect()->route('document.list');
        } catch (RuntimeException $e) {
            $this->error( __('File could not be deleted! Reason: ') . $e->getMessage());
        }
    }

    private function validateFileUpload($input) {

        Validator::make(
            [   'editorFileinput' => $input ], 
            [   'editorFileinput' => 'required|mimes:pdf|max:10240'   ], 
            [
                'editorFileinput.required' => 'A file to upload has to be selected!',
                'editorFileinput.mimes' => 'The uploaded file must be a PDF!',
                'editorFileinput.max' => 'The size of the uploaded file must not exceed 10 MB!'
            ]
        )->validate();
    }

    public function addFile() {
        
        log::info("this->editorFileinput: " . $this->editorFileinput);
        $this->validateFileUpload($this->editorFileinput);

        $currentDate =  date('Y-m-d');
        $this->document->path = $this->editorFileinput->store($currentDate, ['disk' => 'documents']);
        $this->document->saveAndUpdateStatus();

        $this->success( __('File was successfully added to document ' . $this->filename . '.') );
    }
    
    public function save() {
        $this->document->filename = $this->filename;
        $this->document->content = $this->content;
        $tags = explode(",", $this->editorTags);
        $this->document->syncTags($tags);
        $this->document->saveAndUpdateStatus();

        $this->success( __('Canges to document ' . $this->filename . ' were successfully saved.') );
    }

    public function render()
    {
        $isPending = ($this->document->status === DocumentStatus::PENDING) ? 1 : 0;
        
        // inform the user of impossible editing of pending documents
        if ($isPending && $this->mode === 'edit') {
            $this->error( __("This document is still in status 'Pending' and thus cannot be edited!"));
        } 
        // set editmode only if document is not pending
        $isEditMode = (!$isPending && $this->mode === 'edit') ? 1 : 0;

        // goto metadata tab automatically when we are in edit mode
        $tab = $isEditMode ? 'meta' : 'file-viewer';
        
        return view('livewire.documents.editor',
           [
                'badgeHtml' => DocumentHelper::getStatusBadge($this->document->status),
                'tagsHtml' => DocumentHelper::getTagsAsBadges($this->document->simpleTags),
                'isPending' => $isPending,
                'isEditMode' => $isEditMode,
                'tab' => $tab
           ] 
        );
    }
}
