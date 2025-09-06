<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

Route::get('/car', [CarController::class, 'lookup'])->name('car.lookup');


Route::get('/', function () {
    return view('welcome');
});
