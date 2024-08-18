<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

Route::get('/report', [ReportController::class, 'generateReport']);


Route::get('/', function () {
    return view('welcome');
});
