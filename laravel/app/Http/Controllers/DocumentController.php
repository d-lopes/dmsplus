<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Exceptions\InvalidRequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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

        return view('documents.show', [
            'document' => $document
        ]);
    }

}
