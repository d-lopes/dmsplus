<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Exceptions\InvalidRequestException;
use App\Http\Livewire\Documents\DocumentHelper;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DocumentApiController extends Controller
{

    public function list(Request $request) {

        $search_term = $request->input("search");
        if (!isset($search_term)) {
            return new DocumentCollection(Document::paginate());
        }
        
        try {
            list($field, $value) = explode('==', $search_term);
            $documents = Document::where($field, "=", $value)->paginate();    
        } catch(Exception $ex) {
            Log::warning("Caught following exception when searching documents via API: ", [$ex->getMessage()]);
            $documents = [];
        }
        
        return new DocumentCollection($documents);
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
        DocumentHelper::refreshDocumentDates($document);
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
        
        // save file
        $currentDate =  date('Y-m-d');
        $path = $uploadedFile->store($currentDate, ['disk' => 'documents']);

        // update document path and status in DB
        $document->path = $path;
        $document->saveAndUpdateStatus();

        return Storage::url($path);
    }
    
    public function put(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        $document->update($request->all());
        DocumentHelper::refreshDocumentDates($document);
        $document->saveAndUpdateStatus();
        
        return response()->json([
            'message' => 'document with Id successfully updated'
        ], 204);
    }
    
    public function delete(Request $request, $id) {
        $document = Document::find($id);
        if ($document === null) {
            throw new ModelNotFoundException("resource with Id $id does not exist");
        }

        DocumentHelper::handleDeleteAction($document);
    
        return response()->json([
            'message' => 'document with Id successfully deleted'
        ], 204);
    }

}
