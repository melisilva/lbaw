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

//Our routes:
//Home
Route::get('/','Auth\LoginController@home')->name('home');

//Authentication
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

//FAQ 
Route::get('/faq','FaqController@show')->name('faq');

//About us
Route::get('/about','SobreNosController@show')->name('about-us');

//Posts
Route::post('/api/post/create','PostController@create')->name('create_post');
Route::delete('/api/post/{id}','PostController@delete')->name('delete_post');
Route::get('/api/post/{id}','PostController@updatePost')->name('update_post');
Route::post('/api/post/{id}','PostController@editPost')->name('edit_post');

//Admin
Route::prefix('/admin')->name('admin.')->namespace('Admin')->group(function() {    
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('admin-users','AdminController@view')->name('admin_view_users');
    Route::get('admin-usersid-exact-search','AdminController@emsAdminUserid')->name('admin_userid_exact_search');
    Route::get('admin-usersname-exact-search','AdminController@emsUsernome')->name('admin_username_exact_search');
    Route::get('admin-usersemail-exact-search','AdminController@emsUseremail')->name('admin_useremail_exact_search');
    Route::get('admin-usersid-full-text-search','AdminController@ftsUserID')->name('admin_userid_full_text_search');
    Route::get('admin-usersname-full-text-search','AdminController@ftsUserName')->name('admin_username_full_text_search');
    Route::get('admin-usersemail-full-text-search','AdminController@ftsUserEmail')->name('admin_useremail_full_text_search');
});

//Search
Route::get('/api/search-usersnome-exact-search','SearchController@emsUsernome')->name('usernome_exact_match_search');
Route::get('/api/search-usersnomepost-exact-search','SearchController@emsUsernomePost')->name('usernomepost_exact_match_search');
Route::get('/api/search-post-full-text-search','SearchController@ftsUserName')->name('usernome_full_text_search');
Route::get('/api/search-usernome-full-text-search','SearchController@ftsPosts')->name('post_full_text_search');

//Timeline
Route::get('genfeed','TimelineController@generalTimeline')->name('general_feed');
Route::get('fyf','TimelineController@personalizedTimeline')->name('for_you_feed');

//User
Route::get('/profile/{id}','UserController@showProfile')->name('show_profile');
Route::get('/admin/profile/{id}','UserController@showProfiletoAdmin')->name('admin_show_profile');
Route::get('/config/{id}','UserController@config_view')->name('config_view');
Route::post('/config/{id}','UserController@config')->name('config_user');
Route::put('/users/friends/{id}','UserController@colleague')->name('add_friend');
Route::delete('/users/friends/{id}','UserController@breakup')->name('breakup');
Route::get('/users/friends/{id}','UserController@showColleagues')->name('show_friends');
Route::delete('/users/{id}','UserController@delete')->name('delete_user');
Route::post('/user/moderator/{id}','UserController@promoteToModerator')->name('new_moderator');
Route::get('/choose-path','UserController@choosePath')->name('choose_path');
Route::post('/user/estudante','UserController@createEstudante')->name('new_student');
Route::post('/user/docente','UserController@createDocente')->name('new_teacher');
