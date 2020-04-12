<?php

use App\Document;
use App\Http\Resources\DocumentCollection;
use App\Http\Resources\DocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* Document API */
Route::prefix('documents')->group(
    function () {
    
        Route::post('/', 'DocumentApiController@post');

        Route::post('/{id}/binary', 'DocumentApiController@upload');
        
        Route::get('/{id}', 'DocumentApiController@get');
        
        Route::put('/{id}', 'DocumentApiController@put');
        
        Route::delete('/{id}', 'DocumentApiController@delete');

        Route::get('/', 'DocumentApiController@list');
    }
);