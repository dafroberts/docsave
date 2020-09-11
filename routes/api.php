<?php

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

Route::get('/documents/unsorted', 'DocumentController@unsorted')->name('api.documents.unsorted');
Route::post('/document/generate/preview', 'DocumentController@getPreview')->name('api.document.getPreview');
Route::post('/document/store', 'DocumentController@store')->name('api.document.store');
Route::get('/documents/latest', 'DocumentController@latest')->name('api.documents.latest');
Route::get('/documents/index', 'DocumentController@index')->name('api.documents.index');
Route::post('/documents/search', 'DocumentController@search')->name('api.documents.search');

Route::get('/tags', 'TagController@index')->name('api.tags.index');
