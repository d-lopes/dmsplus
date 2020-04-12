<?php

namespace App\Http\Controllers;

use App\Document;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentApiController extends Controller
{

    public function list(Request $request) {
        return new DocumentCollection(Document::paginate());
    }
     
    public function get(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        return new DocumentResource($document);
    }
    
    public function post(CreateDocumentRequest $request) {
        
        // make sure required data is given
        $request->validated(); 
        
        $document = new Document($request->all());
        $document->save();

        return response()->json($document, 201);
    }

    public function upload(Request $request, $id) {
        
        $uploadedFile = $request->file('document');
        if ($uploadedFile === null) {
            throw new InvalidRequestException("no file in field 'document' submitted");
        }
        
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }
        
        $uploadedFile = $request->file('document');
        $currentDate =  date('Y-m-d');
        $path = $uploadedFile->store($currentDate, ['disk' => 'documents']);

        $document->path = $path;
        $document->save();

        return Storage::url($path);
    }
    
    public function put(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        $document->update($request->all());
    
        return response()->json([
            'message' => 'document with Id successfully updated'
        ], 204);
    }
    
    public function delete(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        $document->delete();
    
        return response()->json([
            'message' => 'document with Id successfully deleted'
        ], 204);
    }

}
