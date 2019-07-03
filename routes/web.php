<?php

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
    return view('welcome');
});

Route::get('api/users', 'Api\UserController@index')->middleware('api');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->name('users.avatars')->middleware(['api', 'auth']);

Route::get('/threads', 'ThreadController@index')->name('threads.index');
Route::get('/threads/create', 'ThreadController@create')->name('threads.create')->middleware('auth');;
Route::get('/threads/{channel}', 'ThreadController@index')->name('threads.channel.index');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show')->name('threads.show');
Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy')->name('threads.destroy')->middleware('auth');
Route::post('/threads', 'ThreadController@store')->name('threads.store')->middleware(['auth', 'verified']);
Route::patch('/threads/{channel}/{thread}', 'ThreadController@update')->name('threads.update')->middleware(['auth', 'verified']);


Route::post('/lock-threads/{thread}', 'LockThreadController@store')->name('lock-threads.store')->middleware(['auth', 'admin']);;
Route::delete('/lock-threads/{thread}', 'LockThreadController@destroy')->name('lock-threads.destroy')->middleware(['auth', 'admin']);;

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index')->name('threads.replies.index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('threads.replies.store')->middleware('auth');
Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->name('threads.subscriptions.store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->name('threads.subscriptions.destroy')->middleware('auth');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store')->name('replies.favorites.store')->middleware('auth');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy')->name('replies.favorites.destroy')->middleware('auth');

Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy')->middleware('auth');
Route::patch('/replies/{reply}', 'ReplyController@update')->name('replies.update')->middleware('auth');

Route::post('/replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store')->middleware('auth');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile.show');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index')->name('profile.notifications')->middleware('auth');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy')->name('profile.notifications.destroy')->middleware('auth');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');
