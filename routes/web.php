<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

// Directly add text and create document 
Route::get('/create-document', [DocumentController::class, 'create'])->name('create.document.form');
Route::post('/create-document', [DocumentController::class, 'store'])->name('create.document');

// Upload and Modify
Route::get('/upload-and-modify', [DocumentController::class, 'showUploadForm'])->name('upload.form');
Route::post('/modify-document', [DocumentController::class, 'modifyDocument'])->name('modify.document');

// Upload and Modify 2
Route::get('/upload-and-modify-2', [DocumentController::class, 'showUploadForm2'])->name('upload.form2');
Route::post('/modify-document-2', [DocumentController::class, 'modifyDocument2'])->name('modify.document2');

Route::get('/', function () {
    return view('welcome');
});
