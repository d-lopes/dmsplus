<?php

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

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/documents/search', 'DocumentController@search')->name('document.search');
Route::get('/documents/{id}', 'DocumentController@show')->name('document.show');
Route::post('/documents/{id}/edit', 'DocumentController@edit')->name('document.edit');
Route::post('/documents/{id}/upload', 'DocumentController@upload')->name('document.upload');