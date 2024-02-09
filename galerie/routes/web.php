<?php

use App\Http\Controllers\PhotoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[PhotoController::class,'index']);
Route::post('/store',[PhotoController::class,'store'])->name('store');
Route::post('/download/{photo}',[PhotoController::class, 'download'])->name('download');
Route::delete('/destroy/{photo}',[PhotoController::class, 'destroy'])->name('destroy');
