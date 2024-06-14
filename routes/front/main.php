<?php

use App\Http\Controllers\Front\AboutUsController;
use App\Http\Controllers\Front\AchievementController;
use App\Http\Controllers\Front\AchievementDetailController;
use App\Http\Controllers\Front\ConferenceController;
use App\Http\Controllers\Front\ConferenceDetailController;
use App\Http\Controllers\Front\ContactUsController;
use App\Http\Controllers\Front\DepartmentController;
use App\Http\Controllers\Front\DepartmentDetailController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsAndEventsController;
use App\Http\Controllers\Front\NewsAndEventsDetailController;
use App\Http\Controllers\Front\ResearchController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\TeamController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
|Frontend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register backend web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the prefix "admin" middleware group. Now create something great!
|
*/
//Frontend Routes

Route::get('about-us', [AboutUsController::class, 'index'])->name('about-us');
Route::get('departments', [DepartmentController::class, 'index'])->name('departments');
Route::get('departments-details/{slug}', [DepartmentController::class, 'departmentDetail'])->name('departments-details');

//Route::get('department/{id}/thumbnail/{filename}', [DepartmentController::class, 'getImage'])->name('thumbnail.getImage');
//Route::get('department/{id}/banner/{filename}', [DepartmentController::class, 'getImageBanner'])->name('banner.getImageBanner');
//Route::get('department/{id}/icon/{filename}', [DepartmentController::class, 'getImageIcon'])->name('icon.getImageIcon');

//fetch department attachment
Route::get('departments/{id}/show-images/{filename}', [DepartmentController::class, 'showDepartmentImages'])->name('departments.show-images');


//Route::get('department-details', [DepartmentDetailController::class, 'index'])->name('department-details');
Route::get('our-team', [TeamController::class, 'index'])->name('our-team');
Route::get('our-team/{id}/image/{filename}', [TeamController::class, 'getImage'])->name('our-team.getImage');
Route::get('our-team-detail/{slug}', [TeamController::class, 'detail'])->name('our-team-details');


Route::get('news-and-events', [NewsAndEventsController::class, 'index'])->name('news-and-events');
Route::get('news-and-events/{category}', [NewsAndEventsController::class, 'category'])->name('news-and-events-category');

Route::get('news-and-events/{id}/thumbnail/{filename}', [NewsAndEventsController::class, 'getImageThumbnail'])->name('event-thumbnail.getImageThumbnail');
Route::get('news-and-events/{id}/banner/{filename}', [NewsAndEventsController::class, 'getImageBanner'])->name('event-banner.getImageBanner');
//Route::get('news-and-events-detail', [NewsAndEventsDetailController::class, 'index'])->name('news-and-events-detail');
Route::get('news-and-events-detail/{slug}', [NewsAndEventsDetailController::class, 'index'])->name('news-and-events-details');


Route::get('achievements', [AchievementController::class, 'index'])->name('achievements');
Route::get('achievements/{id}/image/{filename}', [AchievementController::class, 'getImage'])->name('achievements.getImage');
Route::get('achievements-detail/{slug}', [AchievementDetailController::class, 'index'])->name('achievements-detail');


Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact-us');
Route::post('contact-us', [ContactUsController::class, 'store'])->name('contact');


Route::get('research', [ResearchController::class, 'index'])->name('research');
Route::get('get/research', [ResearchController::class, 'getIndex'])->name('get.research');
Route::get('research/{id}/detail', [ResearchController::class, 'detail'])->name('research.detail');
Route::get('research-attachment-url/{research_id}/attach/{filename}', [ResearchController::class, 'getFile'])->name('research-attachment-url.attach');

//Route::get('research-attachment/{id}/attachment/{filename}', [ResearchController::class, 'getFile'])->name('research-attachment.getFile');

//Route::get('conference', [ConferenceController::class, 'index'])->name('conference');
//Route::get('conference/image/{filename}', [ConferenceController::class, 'getImage'])->name('conference.getImage');
//Route::get('conference-detail/{slug}', [ConferenceDetailController::class, 'index'])->name('conference-detail');
//Route::get('conference/{category}', [ConferenceController::class, 'category'])->name('conference-category');
