<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

# create new document via webapp
Route::prefix('/new-documents')->group(
    function () {
        
        Route::get('/', [DocumentController::class, 'newDocument'])->name('document.new');
        Route::post('/', [DocumentController::class, 'create'])->name('document.create');
        
    }
);

# view and manage existing documents via webapp
Route::prefix('/documents')->group(
    function () {

        Route::post('/search', [DocumentController::class, 'search'])->name('document.search');
        Route::get('/{id}', [DocumentController::class, 'show'])->name('document.show');
        # edit, complete or delete document
        Route::post('/{id}/edit', [DocumentController::class, 'edit'])->name('document.edit');
        Route::post('/{id}/add-file', [DocumentController::class, 'addFile'])->name('document.addFile');
        Route::post('/{id}/delete', [DocumentController::class, 'delete'])->name('document.delete');

    }
);

