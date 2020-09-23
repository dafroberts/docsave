<?php

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
    return view('dashboard');
})->name('dashboard');

Route::get('documents/unsorted', function() {
    return view('documents.unsorted');
})->name('docs.unsorted');

Route::get('documents/search', function() {
    return view('documents.search');
})->name('docs.search');

Route::get('documents/backups', function() {
    return view('backups.index');
})->name('docs.backups');

Route::get('documents/download/{document}', 'DocumentController@download')->name('docs.download');
Route::get('backups/download/{backup}', 'BackupController@download')->name('backups.download');

// Route::get('documents/unsorted', 'DocumentController@unsorted')->name('documents.unsorted');
