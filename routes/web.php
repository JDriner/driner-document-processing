<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

// Directly add text and create document 
Route::get('/create-document', [DocumentController::class, 'create'])->name('create.document.form');
Route::post('/create-document', [DocumentController::class, 'store'])->name('create.document');

// Upload and Modify
Route::get('/upload-and-modify', [DocumentController::class, 'showUploadForm'])->name('upload.form');
Route::post('/modify-document', [DocumentController::class, 'modifyDocument'])->name('modify.document');

Route::get('/', function () {
    return view('welcome');
});
