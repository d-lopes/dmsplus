<?php

namespace App\Http\Livewire\Documents;

use App\Events\DocumentEvents;
use App\Http\Livewire\Common\ComponentBase;
use App\Models\Document;
use App\Models\DocumentStatus;
use Livewire\WithFileUploads;

class AddButton extends ComponentBase {

    use WithFileUploads;

    public $dialog = false;

    public $fileinput;
    public $filename;

    protected $listeners = ['showModal', 'closeModal'];

    protected $rules = [
        'fileinput' => 'required|mimes:pdf|max:10240',
        'filename' => 'required|string'
    ];

    protected $messages = [
        'fileinput.required' => 'A file to upload has to be selected!',
        'fileinput.mimes' => 'The uploaded file must be a PDF!',
        'fileinput.max' => 'The size of the uploaded file must not exceed 10 MB!',
        'filename.required' => 'The input field "File name" cannot be empty!',
    ];

    public function showDialog() {
        $this->resetValidation();
        $this->reset(['fileinput', 'filename']);
        $this->dialog = true;
    }

    public function closeDialog() {
        $this->dialog = false;
    }

    public function save() {
        $this->validate();

        $document = new Document();
        $document->filename = $this->filename;
        $document->path = $this->fileinput->store("raw-files", ['disk' => 'uploads']);
        $document->status = DocumentStatus::PENDING;
        $document->save();

        $this->success( __('Document ' . $this->filename . ' was successfully saved.') );
        $this->emit(DocumentEvents::CREATED);

        $this->closeDialog();

        redirect()->route('document.list');
    }

    public function render() {
        return view('livewire.documents.add-button');
    }
}
