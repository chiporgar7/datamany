<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportData\ImportFoliosController;
use App\Http\Controllers\ImportData\ImportTransactionsController;


Route::post('/import/transactions', ImportTransactionsController::class);
Route::post('/import/folios', ImportFoliosController::class);
