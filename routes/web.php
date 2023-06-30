<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvertedListController;

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

Route::get('/', [ConvertedListController::class, 'index'])->name('index');
Route::post('/', [ConvertedListController::class, 'store'])->name('store');