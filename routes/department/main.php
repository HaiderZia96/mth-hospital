<?php


use App\Http\Controllers\Department\DashboardController;
use App\Http\Controllers\Department\DepartmentBannerController;
use App\Http\Controllers\Department\DepartmentController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the prefix "admin" middleware group. Now create something great!
|
*/
//Backend Routes
Route::group(['middleware' => ['auth','verified','xss','user.status','user.module:department'], 'prefix' => 'department','as' => 'department.'], function() {
    //Dashboard
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');

    //Department Banner
    Route::resource('dept-banner', DepartmentBannerController::class);
    Route::get('get-dept-banner', [DepartmentBannerController::class, 'getIndex'])->name('get.dept-banner');
    Route::get('dept-banner-status/{id}', [DepartmentBannerController::class, 'deptBannerStatus'])->name('dept-banner-status');
    Route::get('get-dept-banner-activity/{id}', [DepartmentBannerController::class, 'getActivity'])->name('get.dept-banner-activity');
    Route::get('get-dept-banner-activity-log/{id}', [DepartmentBannerController::class, 'getActivityLog'])->name('get.dept-banner-activity-log');
    Route::get('get-dept-banner-activity-trash', [DepartmentBannerController::class, 'getTrashActivity'])->name('get.dept-banner-activity-trash');
    Route::get('get-dept-banner-activity-trash-log', [DepartmentBannerController::class, 'getTrashActivityLog'])->name('get.dept-banner-activity-trash-log');


});



