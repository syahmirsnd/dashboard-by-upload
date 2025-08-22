<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;

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

Route::get('/', function () {
    return view('upload');
});


Route::post('/upload', [ExcelController::class, 'upload'])->name('excel.upload');
Route::get('/upload', [ExcelController::class, 'showUpload'])->name('excel.form');

Route::get('/dashboard', [ExcelController::class, 'dashboard'])->name('excel.dashboard');

