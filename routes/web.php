<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FrontController;

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

/*
|--------------------------------------------------------------------------
|---- Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware('LoginCheck')->get('/admin/auth/{any}', [AdminController::class, 'admin'])->where('any', '.*')->name('lvs.admin.auth.any');
Route::middleware('LoginCheck')->get('/admin', [AdminController::class, 'admin'])->where('any', '.*')->name('lvs.admin');
Route::middleware('LoginCheck')->get('/admin/{any}', [AdminController::class, 'admin'])->where('any', '.*')->name('lvs.admin.any');
Route::get('/admin', function () { return redirect()->route('lvs.admin.any', 'dashboard'); } );

/*
|--------------------------------------------------------------------------
|---- User Routes
|--------------------------------------------------------------------------
*/

Route::get('/user/auth/{any}', [FrontController::class, 'user'])->where('any', '.*')->name('lvs.user.auth.any');
Route::get('/user/', [FrontController::class, 'user'])->where('any', '.*')->name('lvs.user');
Route::get('/user/{any}', [FrontController::class, 'user'])->where('any', '.*')->name('lvs.user.any');
Route::get('/', function (){ return redirect()->route('lvs.user.any','home'); });
