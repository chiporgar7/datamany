<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportData\ImportFoliosController;
use App\Http\Controllers\ImportData\ImportTransactionsController;


Route::post('/import/transactions', ImportTransactionsController::class);
Route::post('/import/folios', ImportFoliosController::class);
Route::get('/import/progress/{progressId}', [\App\Http\Controllers\Api\ImportProgressController::class, 'show']);

Route::get('/search/transactions', \App\Http\Controllers\Api\SearchTransactionsController::class);
Route::get('/search/folios', \App\Http\Controllers\Api\SearchFoliosController::class);

Route::prefix('metrics')->group(function () {
    Route::get('/total-money', [\App\Http\Controllers\Api\MetricsController::class, 'totalMoney']);
    Route::get('/top-users', [\App\Http\Controllers\Api\MetricsController::class, 'topUsers']);
});
