<?php

use App\Http\Controllers\DocumentApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Document API */
Route::prefix('documents')->group(
    function () {
    
        Route::post('/', [DocumentApiController::class, 'post']);

        Route::post('/{id}/binary', [DocumentApiController::class, 'upload']);
        
        Route::get('/{id}', [DocumentApiController::class, 'get']);
        
        Route::put('/{id}', [DocumentApiController::class, 'put']);
        
        Route::delete('/{id}', [DocumentApiController::class, 'delete']);

        Route::get('/', [DocumentApiController::class, 'list']);
    }
);