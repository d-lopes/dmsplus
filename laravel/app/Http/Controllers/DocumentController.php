<?php

namespace App\Http\Controllers;

use App\Document;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\CreateDocumentWithFileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DocumentController extends Controller
{

    public function newDocument() {
        return view('documents.create-new');
    }

    public function create(CreateDocumentWithFileRequest $request) {
    
        // make sure required data is given
        $request->validated(); 

        $uploadedFile = $request->file('file');
        if ($uploadedFile === null) {
            throw new InvalidRequestException("no file in field 'file' submitted");
        }
        $path = $uploadedFile->store("raw-files", ['disk' => 'uploads']);

        $document = new Document($request->all());
        $document->path = $path;
        $document->status = "pending";
        $document->save();

        // return to homepage
        return redirect('home');
    }
    
    public function search(Request $request) {
        
        $search_term = $request->input('st');

        $documents = Document::search($search_term)->paginate(10);

        return view('documents.search', [
            'documents' => $documents,
            'searchterm' => $request->st
        ]);
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

    public function delete(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("document with Id $id does not exist");
        }

        // delete file from storage, if it exists
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            Storage::disk('documents')->delete($document->path);
        }

        // make sure the file is really gone
        $exists = Storage::disk('documents')->exists($document->path);
        if ($exists) {
            throw new RuntimeException("file at $document->path could not be deleted");
        }

        // delete model from DB
        $document->delete();

        // return to homepage
        return redirect('home');
    }

}
