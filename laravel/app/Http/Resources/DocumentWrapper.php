<?php

namespace App\Http\Resources;

use App\Http\Livewire\Documents\DocumentHelper;
use App\Models\Document;
use App\Models\DocumentDate;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DocumentWrapper {

    protected $document;

    function __construct(Document $document) {
        $this->document = $document;
    }

    private function refreshDocumentDates() {
        // Guard: if content did not change, then there is no need to refresh dates
        if ($this->document->isClean('content')) {
            return;
        }

        // delete all existing dates for the document
        $this->document->dates()->delete();

        // extract new dates from textual content and add them to the document again
        $dateArr = DocumentHelper::extractDocumentDates($this->document->content);
        foreach ($dateArr as $date) {
            $this->document->dates()->save(new DocumentDate(['date_value' => $date]));
        }
    }

    private function updateStatus() {
        // Guard: if content and path did not change, then there is no need to update the status
        if ($this->document->isClean('content') && $this->document->isClean('path')) {
            return;
        }

        if ( empty ($this->document->content) || empty ($this->document->path)) { 
            $this->document->markAsIncomplete();
        } else { 
            $this->document->markAsPublished();
        }
    }

    public function saveAndEnrich(array $options = []) {
        // enrich with derived information from raw data
        $this->refreshDocumentDates();
        $this->updateStatus();
        
        // save document
        $this->document->save($options);
    }

    public function handleDeleteAction() {
        // delete file from storage, if it exists
        $exists = Storage::disk('documents')->exists($this->document->path);
        if ($exists) {
            Storage::disk('documents')->delete($this->document->path);
        }

        // make sure the file is really gone
        $exists = Storage::disk('documents')->exists($this->document->path);
        if ($exists) {
            throw new RuntimeException('File at ' . $this->document->path . ' could not be deleted!');
        } else {
            $this->document->delete();
        }
    }
        
}