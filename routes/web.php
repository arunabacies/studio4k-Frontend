<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth/login');
})->name('getlogin');

/* Login controller routes */
Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('login');
Route::get('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');
Route::get('/register/{token}', 'App\Http\Controllers\AuthController@register')->name('register');
Route::post('/signup', 'App\Http\Controllers\AuthController@signup')->name('signup');
Route::get('/forgot-password', 'App\Http\Controllers\AuthController@forgotPassword')->name('forgot-password');
Route::post('/password-reset-link', 'App\Http\Controllers\AuthController@passwordResetLink')->name('password-reset-link');
Route::get('/reset-password/{token}', 'App\Http\Controllers\AuthController@resetPassword')->name('reset-password');
Route::post('/update-password', 'App\Http\Controllers\AuthController@updatePassword')->name('update-password');

Route::get('/project/{page}', 'App\Http\Controllers\ProjectController@index')->name('project');
Route::get('/project/view/{id}', 'App\Http\Controllers\ProjectController@viewProject')->name('view-project');
Route::post('/project/addProject', 'App\Http\Controllers\ProjectController@addProject')->name('add-project');
Route::post('/project/editProject', 'App\Http\Controllers\ProjectController@editProject')->name('edit-project');
Route::post('/project/deleteProject', 'App\Http\Controllers\ProjectController@deleteProject')->name('delete-project');
Route::post('/project/createEvent', 'App\Http\Controllers\ProjectController@createEvent')->name('create-event');
Route::post('/project/deleteEvent', 'App\Http\Controllers\ProjectController@deleteEvent')->name('delete-event');
Route::post('/project/editEvent', 'App\Http\Controllers\ProjectController@editEvent')->name('edit-event');
Route::post('/project/terminate-session', 'App\Http\Controllers\ProjectController@terminateSession')->name('terminate-session');
Route::post('/project/generate-video-call-link', 'App\Http\Controllers\ProjectController@generateVideoCallLink')->name('generate-video-call-link');
Route::post('/project/create-update-presenter', 'App\Http\Controllers\ProjectController@createUpdatePresenter')->name('create-update-presenter');
Route::post('/project/removeProjectUser', 'App\Http\Controllers\ProjectController@removeProjectUser')->name('remove-user');
Route::post('/project/presenter-email-sent', 'App\Http\Controllers\ProjectController@presenterEmailSent')->name('presenter-email-sent');
Route::post('/project/multimedia-upload', 'App\Http\Controllers\ProjectController@multimediaUpload')->name('multimedia-upload');
Route::post('/project/multimedia-delete', 'App\Http\Controllers\ProjectController@multimediaDelete')->name('multimedia-delete');
Route::get('/recordings/{page}', 'App\Http\Controllers\ProjectController@recordings')->name('recordings');

Route::get('/user-settings/{page}', 'App\Http\Controllers\UserController@index')->name('user-settings');
Route::post('/user/addUser', 'App\Http\Controllers\UserController@addUser')->name('addUser');
Route::post('/user/deleteUser','App\Http\Controllers\UserController@deleteUser')->name('delete-user');
Route::post('/user/edit-user', 'App\Http\Controllers\UserController@editUser')->name('edit-user');
Route::get('/user/user-profile', 'App\Http\Controllers\UserController@getProfile')->name('user-profile');
Route::post('/user/edit-user-profile', 'App\Http\Controllers\UserController@editProfile')->name('edit-profile');

Route::get('/settings/timezone', 'App\Http\Controllers\SettingsController@index')->name('time-settings');
Route::post('/settings/edit-timezone', 'App\Http\Controllers\SettingsController@editTimezone')->name('edit-time-settings');

Route::post('/project/event', 'App\Http\Controllers\ProjectController@getEventForEdit')->name('get-event');
Route::post('/project/presenters', 'App\Http\Controllers\ProjectController@getPresenterForEdit')->name('get-presenter');
Route::get('/project/recordings/{id}/{session_id}', 'App\Http\Controllers\ProjectController@recordings')->name('project-recordings');

Route::get('/studio/{page}', 'App\Http\Controllers\StudioController@index')->name('studio');
Route::post('/studio/add', 'App\Http\Controllers\StudioController@add')->name('add-studio');
Route::post('/studio/edit', 'App\Http\Controllers\StudioController@edit')->name('edit-studio');
Route::post('/studio/delete', 'App\Http\Controllers\StudioController@delete')->name('delete-studio');
Route::get('/studio/view/{id}', 'App\Http\Controllers\StudioController@view')->name('view-studio');
Route::post('/studio/createSession', 'App\Http\Controllers\StudioController@createSession')->name('create-studio-session');
Route::post('/studio/deleteSession', 'App\Http\Controllers\StudioController@deleteSession')->name('delete-studio-session');
Route::post('/studio/editSession', 'App\Http\Controllers\StudioController@editSession')->name('edit-studio-session');
Route::post('/studio/session', 'App\Http\Controllers\StudioController@getSessionForEdit')->name('get-session');
Route::post('/studio/create-update-presenter', 'App\Http\Controllers\StudioController@createUpdatePresenter')->name('create-update-studio-presenter');
Route::post('/studio/presenters', 'App\Http\Controllers\StudioController@getPresenterForEdit')->name('get-studio-presenter');
Route::post('/studio/terminate-session', 'App\Http\Controllers\StudioController@terminateSession')->name('terminate-studio-session');
Route::post('/studio/presenter-email-sent', 'App\Http\Controllers\StudioController@presenterEmailSent')->name('studio-presenter-email-sent');
Route::post('/studio/record-session', 'App\Http\Controllers\StudioController@recordSession')->name('studio-record-session');
Route::post('/studio/go-link', 'App\Http\Controllers\StudioController@goLink')->name('go-link');
Route::get('/studio/recordings/{id}/{session_id}', 'App\Http\Controllers\StudioController@recordings')->name('studio-recordings');
Route::post('/gmail/login', 'App\Http\Controllers\StudioController@gmailLogin')->name('gmail-login');
Route::post('/dropbox/login', 'App\Http\Controllers\StudioController@dropboxLogin')->name('dropbox-login');
Route::get('/storage-credentials', 'App\Http\Controllers\StudioController@storageCredentials')->name('storage-credentials');


