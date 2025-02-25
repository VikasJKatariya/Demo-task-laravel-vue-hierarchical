<?php

use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pages', [PageController::class, 'index']);
Route::resource('adminpages', AdminPageController::class);
Route::get('pages/{any}', [PageController::class, 'showData'])->where('any', '.*');
