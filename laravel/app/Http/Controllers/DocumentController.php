<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\CreateDocumentWithFileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DocumentController extends Controller
{

    public function list() {
        return view('documents.list');
    }

    public function show(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("document with Id $id does not exist");
        }

        return view('documents.single-view', [
            'document' => $document,
            'searchterm' => $request->st
        ]);
    }

    public function edit(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("document with Id $id does not exist");
        }
        $content = $request->input('content');
        $document->content = $content;
        $document->saveAndUpdateStatus();        

        return view('documents.single-view', [
            'document' => $document,
            'searchterm' => $request->st
        ]);
    }

    public function addFile(Request $request, $id) {
        $uploadedFile = $request->file('file');
        if ($uploadedFile === null) {
            throw new InvalidRequestException("no file in field 'file' submitted");
        }
        
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }
        
        // save file
        $currentDate =  date('Y-m-d');
        $path = $uploadedFile->store($currentDate, ['disk' => 'documents']);

        // update document path and status in DB
        $document->path = $path;
        $document->saveAndUpdateStatus();

        return view('documents.single-view', [
            'document' => $document,
            'searchterm' => $request->st
        ]);
    }

}
