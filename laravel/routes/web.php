<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

# view and manage existing documents via webapp
Route::prefix('/documents')->group(
    function () {

        Route::get('/', [DocumentController::class, 'list'])->name('document.list');
        
        Route::get('/{id}', [DocumentController::class, 'show'])->name('document.show');
        Route::post('/{id}/edit', [DocumentController::class, 'edit'])->name('document.edit');
        
        #Route::post('/search', [DocumentController::class, 'search'])->name('document.search');        
        #Route::post('/{id}/add-file', [DocumentController::class, 'addFile'])->name('document.addFile');
        #Route::post('/{id}/delete', [DocumentController::class, 'delete'])->name('document.delete');

    }
);


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
