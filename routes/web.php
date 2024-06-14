<?php

use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;
//Import Admin Routes
require __DIR__.'/auth.php';
require __DIR__.'/admin/main.php';
require __DIR__.'/manager/main.php';
 require __DIR__.'/department/main.php';
require __DIR__.'/front/main.php';
require __DIR__.'/command.php';

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

Route::get('/', [HomeController::class, 'index'])->name('home');

