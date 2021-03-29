<?php

use App\Http\Controllers\DocumentController;
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

Route::middleware(['auth:sanctum', 'verified'])->group(
    function () { 
        Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
        Route::get('/documents', [DocumentController::class, 'list'])->name('document.list');
        Route::get('/documents/{id}', [DocumentController::class, 'show'])->name('document.show');
    }
);