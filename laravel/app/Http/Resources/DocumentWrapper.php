<?php

namespace App\Http\Resources;

use App\Http\Livewire\Documents\DocumentHelper;
use App\Models\Document;
use App\Models\DocumentDate;
use Illuminate\Support\Facades\Storage;
use Mimey\MimeTypes;
use RuntimeException;

/**
 * Wrapper Class to handle more complex update and delete scenarios. Code that is part of this class does not belong in the 
 * Eloquent Model for Documents.
 * 
 */
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

    private function updateFileSize() {
        // Guard: if path did not change, then there is no need to update the file size
        if (isset($this->document->size) && $this->document->isClean('path')) {
            return;
        }

        // calculate and set file size
        if (Storage::disk('documents')->exists($this->document->path)) {
            $this->document->size = Storage::disk('documents')->size($this->document->path);
        }
    }

    private function updateMimeType() {
        // Guard: if path did not change, then there is no need to update the mime type
        if (isset($this->document->mime_type) && $this->document->isClean('path')) {
            return;
        }

        // derive and set the mime type
        if (Storage::disk('documents')->exists($this->document->path)) {
            $mimes = new MimeTypes();
            $ext = pathinfo($this->document->path, PATHINFO_EXTENSION);
            $this->document->mime_type = $mimes->getMimeType($ext);
        }
    }

    private function updateMd5Hash() {
        // Guard: if path did not change, then there is no need to update the hash value
        if (isset($this->document->md5_hash) && $this->document->isClean('content')) {
            return;
        }

        // calculate and set hash value
        $this->document->md5_hash = DocumentHelper::generateHashValue($this->document->content);
    }

    private function updateStatus() {
        // Guard: if content and path did not change, then there is no need to update the status
        if ($this->document->isClean('content') && $this->document->isClean('path')) {
            return;
        }

        // set status depending on availability of path and content of the document
        if ( empty ($this->document->content) || empty ($this->document->path)) { 
            $this->document->markAsIncomplete();
        } else { 
            $this->document->markAsPublished();
        }
    }

    public function saveAndEnrich(array $options = []) {
        // enrich with derived information from raw data
        $this->refreshDocumentDates();
        $this->updateFileSize();
        $this->updateMimeType();
        $this->updateMd5Hash();
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