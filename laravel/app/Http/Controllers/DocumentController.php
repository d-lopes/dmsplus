<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function search(Request $request) {
        
        $search_term = $request->input('st');

        $documents = Document::search($search_term)->get();

        return view('documents.search', [
            'documents' => $documents,
            'searchterm' => $request->st
        ]);
    }

    public function show(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        return view('documents.single-view', [
            'document' => $document,
            'searchterm' => $request->st
        ]);
    }

}
