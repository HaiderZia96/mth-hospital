<?php

use Illuminate\Support\Facades\Artisan;
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

////Initialization//
//Route::get('/init', function() {
//  Artisan::call('key:generate');
//  Artisan::call('view:clear');
//  Artisan::call('config:clear');
//  Artisan::call('cache:clear');
//  Artisan::call('view:clear');
//  Artisan::call('migrate:fresh --seed');
//});
//Refresh//
Route::get('/refresh', function() {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    Artisan::call('config:cache');
//    Artisan::call('view:cache');
});

Route::get('/migrate', function() {
    Artisan::call('migrate');
});
