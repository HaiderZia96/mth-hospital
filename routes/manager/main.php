<?php

use App\Http\Controllers\Manager\AchievementController;
use App\Http\Controllers\Manager\AttachmentController;
use App\Http\Controllers\Manager\ConferenceController;
use App\Http\Controllers\Manager\ContactUsController;
use App\Http\Controllers\Manager\DashboardController;
use App\Http\Controllers\Manager\DepartmentController;
use App\Http\Controllers\Manager\EventCategoryController;
use App\Http\Controllers\Manager\NewsEventController;
use App\Http\Controllers\Manager\ProfileController;
use App\Http\Controllers\Manager\ResearchController;
use App\Http\Controllers\Manager\TeamMemberController;
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
Route::group(['middleware' => ['auth', 'verified', 'xss', 'user.status', 'user.module:manager'], 'prefix' => 'manager', 'as' => 'manager.'], function () {

    //Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Profile
    Route::get('profile/{id}', [ProfileController::class, 'edit'])->name('profile');
    Route::put('profile/{id}', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('profile-image/{id}', [ProfileController::class, 'getImage'])->name('profile.get.image');

    //Department
    Route::resource('dept', DepartmentController::class)->withoutMiddleware('xss');
    Route::get('get-dept', [DepartmentController::class, 'getIndex'])->name('get.dept');
    //update status
    Route::get('dept/{id}/update-department-status', [DepartmentController::class, 'updateStatus'])->name('dept.update-department-status');
    //view attachments
    Route::get('dept/{id}/get-department-attachment/{file_type}', [DepartmentController::class, 'showDepartmentAttachment'])->name('get.department-attachment');
    // Ck Editor File Upload
    Route::post('ckeditor/dept', [DepartmentController::class, 'ckeditorUpload'])->name('dept-upload-ckeditor');
    // Department Priority Routes
    Route::get('swap-up/dept/{id}', [DepartmentController::class, 'swapUp'])->name('swap-up.dept');
    Route::get('swap-down/dept/{id}', [DepartmentController::class, 'swapDown'])->name('swap-down.dept');
    //Department Activities
    Route::get('get-dept-activity/{id}', [DepartmentController::class, 'getActivity'])->name('get.dept-activity');
    Route::get('get-dept-activity-log/{id}', [DepartmentController::class, 'getActivityLog'])->name('get.dept-activity-log');
    Route::get('get-dept-activity-trash', [DepartmentController::class, 'getTrashActivity'])->name('get.dept-activity-trash');
    Route::get('get-dept-activity-trash-log', [DepartmentController::class, 'getTrashActivityLog'])->name('get.dept-activity-trash-log');

    //Event Category
    Route::resource('event-category', EventCategoryController::class);
    Route::get('get-event-category', [EventCategoryController::class, 'getIndex'])->name('get.event-category');
    Route::get('get-event-category-activity/{id}', [EventCategoryController::class, 'getActivity'])->name('get.event-category-activity');
    Route::get('get-event-category-activity-log/{id}', [EventCategoryController::class, 'getActivityLog'])->name('get.event-category-activity-log');
    Route::get('get-event-category-activity-trash', [EventCategoryController::class, 'getTrashActivity'])->name('get.event-category-activity-trash');
    Route::get('get-event-category-activity-trash-log', [EventCategoryController::class, 'getTrashActivityLog'])->name('get.event-category-activity-trash-log');

    //News & Events
    Route::resource('news-event', NewsEventController::class)->withoutMiddleware('xss');
    Route::get('get-news-event', [NewsEventController::class, 'getIndex'])->name('get.news-event');
    //Selectors
    Route::get('get-event-category-select', [NewsEventController::class, 'getEventCategoryIndexSelect'])->name('get.event-category-select');
    Route::get('get-event-department-select', [NewsEventController::class, 'getEventDepartmentIndexSelect'])->name('get.event-department-select');
    //Activity Routes
    Route::get('get-news-event-activity/{id}', [NewsEventController::class, 'getActivity'])->name('get.news-event-activity');
    Route::get('get-news-event-activity-log/{id}', [NewsEventController::class, 'getActivityLog'])->name('get.news-event-activity-log');
    Route::get('get-news-event-activity-trash', [NewsEventController::class, 'getTrashActivity'])->name('get.news-event-activity-trash');
    Route::get('get-news-event-activity-trash-log', [NewsEventController::class, 'getTrashActivityLog'])->name('get.news-event-activity-trash-log');
    // Ck Editor File Upload
    Route::post('ckeditor/news-event', [NewsEventController::class, 'ckeditorUpload'])->name('news-event-upload-ckeditor');
    //view event attachment
    Route::get('news-event/{id}/get-news-attachment/{file_type}', [NewsEventController::class, 'showNewsAndEventAttachment'])->name('news-event.get-news-attachment');

    // News & Events Priority Routes
    Route::get('swap-up/news-event/{id}', [NewsEventController::class, 'swapUp'])->name('swap-up.news-event');
    Route::get('swap-down/news-event/{id}', [NewsEventController::class, 'swapDown'])->name('swap-down.news-event');


    //Team Member
    Route::resource('team-member', TeamMemberController::class)->withoutMiddleware('xss');
    Route::get('get-team-member', [TeamMemberController::class, 'getIndex'])->name('get.team-member');
    Route::get('get-member-department-select', [TeamMemberController::class, 'getDepartmentIndexSelect'])->name('get.member-department-select');
    Route::get('get-team-member-activity/{id}', [TeamMemberController::class, 'getActivity'])->name('get.team-member-activity');
    Route::get('get-team-member-activity-log/{id}', [TeamMemberController::class, 'getActivityLog'])->name('get.team-member-activity-log');
    Route::get('get-team-member-activity-trash', [TeamMemberController::class, 'getTrashActivity'])->name('get.team-member-activity-trash');
    Route::get('get-team-member-activity-trash-log', [TeamMemberController::class, 'getTrashActivityLog'])->name('get.team-member-activity-trash-log');
    // Ck Editor File Upload
    Route::post('ckeditor/team-member', [TeamMemberController::class, 'ckeditorUpload'])->name('team-member-upload-ckeditor');
    //view team member attachment
    Route::get('team-member/{id}/get-attachment/{file_type}', [TeamMemberController::class, 'showTeamMemberImages'])->name('team-member.get-attachment');

    //Achievement
    Route::resource('achievement', AchievementController::class);
    Route::get('get-achievement', [AchievementController::class, 'getIndex'])->name('get.achievement')->withoutMiddleware('xss');
    Route::get('get-achievement-department-select', [AchievementController::class, 'getAchievementDepartmentIndexSelect'])->name('get.achievement-department-select');
    Route::get('get-achievement-activity/{id}', [AchievementController::class, 'getActivity'])->name('get.achievement-activity');
    Route::get('get-achievement-activity-log/{id}', [AchievementController::class, 'getActivityLog'])->name('get.achievement-activity-log');
    Route::get('get-achievement-activity-trash', [AchievementController::class, 'getTrashActivity'])->name('get.achievement-activity-trash');
    Route::get('get-achievement-activity-trash-log', [AchievementController::class, 'getTrashActivityLog'])->name('get.achievement-activity-trash-log');
    // Ck Editor File Upload
    Route::post('ckeditor/achievement', [AchievementController::class, 'ckeditorUpload'])->name('achievement-upload-ckeditor');
    //view achievement attachment
    Route::get('achievement/{id}/get-attachment/{file_type}', [AchievementController::class, 'showAchievementImages'])->name('achievement.get-attachment');

    //Contact Us
    Route::resource('contact-us', ContactUsController::class)->withoutMiddleware('xss');
    Route::get('get-contact-us', [ContactUsController::class, 'getIndex'])->name('get.contact-us');
    Route::get('get-contact-us-activity/{id}', [ContactUsController::class, 'getActivity'])->name('get.contact-us-activity');
    Route::get('get-contact-us-activity-log/{id}', [ContactUsController::class, 'getActivityLog'])->name('get.contact-us-activity-log');
    Route::get('get-contact-us-activity-trash', [ContactUsController::class, 'getTrashActivity'])->name('get.contact-us-activity-trash');
    Route::get('get-contact-us-activity-trash-log', [ContactUsController::class, 'getTrashActivityLog'])->name('get.contact-us-activity-trash-log');


    //Researches
    Route::resource('research', ResearchController::class)->withoutMiddleware('xss');
    Route::get('get-research', [ResearchController::class, 'getIndex'])->name('get.research');
    Route::get('get-research-department-select', [ResearchController::class, 'getDepartmentIndexSelect'])->name('get.research-department-select');
    Route::get('get-research-activity/{id}', [ResearchController::class, 'getActivity'])->name('get.research-activity');
    Route::get('get-research-activity-log/{id}', [ResearchController::class, 'getActivityLog'])->name('get.research-activity-log');
    Route::get('get-research-activity-trash', [ResearchController::class, 'getTrashActivity'])->name('get.research-activity-trash');
    Route::get('get-research-activity-trash-log', [ResearchController::class, 'getTrashActivityLog'])->name('get.research-activity-trash-log');

    Route::get('research/{id}/detail', [ResearchController::class, 'detail'])->name('research.detail');
//    Route::put('research-detail/{id}', [ResearchController::class, 'detailUpdate'])->name('research-detail')->withoutMiddleware('xss');

    // Ck Editor File Upload
    Route::post('ckeditor/research', [ResearchController::class, 'ckeditorUpload'])->name('research-upload-ckeditor');

    // Research Priority Routes
    Route::get('swap-up/research/{id}', [ResearchController::class, 'swapUp'])->name('swap-up.research');
    Route::get('swap-down/research/{id}', [ResearchController::class, 'swapDown'])->name('swap-down.research');

    Route::resource('/research/{rid}/attachment', AttachmentController::class)->withoutMiddleware('xss');;
    Route::get('/research/{rid}/get-attachment', [AttachmentController::class, 'getIndex'])->name('get.attachment');

    //Conferences
    Route::resource('conference', ConferenceController::class)->withoutMiddleware('xss');
    Route::get('get-conference', [ConferenceController::class, 'getIndex'])->name('get.conference');
    Route::get('get-conference-department-select', [ConferenceController::class, 'getDepartmentIndexSelect'])->name('get.conference-department-select');
    Route::get('get-conference-activity/{id}', [ConferenceController::class, 'getActivity'])->name('get.conference-activity');
    Route::get('get-conference-activity-log/{id}', [ConferenceController::class, 'getActivityLog'])->name('get.conference-activity-log');
    Route::get('get-conference-activity-trash', [ConferenceController::class, 'getTrashActivity'])->name('get.conference-activity-trash');
    Route::get('get-conference-activity-trash-log', [ConferenceController::class, 'getTrashActivityLog'])->name('get.conference-activity-trash-log');
    // Ck Editor File Upload
    Route::post('ckeditor/conference', [ConferenceController::class, 'ckeditorUpload'])->name('conference-upload-ckeditor');

    // Research Priority Routes
    Route::get('swap-up/conference/{id}', [ConferenceController::class, 'swapUp'])->name('swap-up.conference');
    Route::get('swap-down/conference/{id}', [ConferenceController::class, 'swapDown'])->name('swap-down.conference');


});

// CK Editor Image routes
Route::get('ck/dept/{filename}', [DepartmentController::class, 'ckImage'])->name('dept.ckImage');
Route::get('ck/news-event/{filename}', [NewsEventController::class, 'ckImage'])->name('news-event.ckImage');
Route::get('ck/team-member/{filename}', [TeamMemberController::class, 'ckImage'])->name('team-member.ckImage');
Route::get('ck/achievement/{filename}', [AchievementController::class, 'ckImage'])->name('achievement.ckImage');
Route::get('ck/conference/{filename}', [ConferenceController::class, 'ckImage'])->name('conference.ckImage');
Route::get('ck/research/{filename}', [ResearchController::class, 'ckImage'])->name('research.ckImage');
