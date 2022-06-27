<?php

use App\Http\Controllers\Api\v0\FrontendController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;

$namespace = 'App\Http\Controllers\Api\v0';

Route::group(['prefix' => 'v0'], function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('test',[AuthController::class, 'test']);
});

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v0'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('get_user', [AuthController::class, 'get_user']);
    Route::get('users', [AuthController::class, 'users']);
    Route::get('users/{id}', [AuthController::class, 'showUser']);
    Route::post('users/{id}', [AuthController::class, 'updateUser']);

    Route::resource('roles', RoleController::class);
});


Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v0', 'namespace' => $namespace], function () {
    Route::resource('settings', 'SettingController');
    Route::resource('layout-widgets', 'LayoutWidgetController');
    Route::get('l-w-s', 'LayoutWidgetController@layoutWidget');
    Route::get('taxonomy-filter-data', 'LayoutWidgetController@filterData');

    Route::resource('categories', 'CategoryController');
    Route::resource('tags', 'TagController');
    Route::resource('menus', 'MenuController');

    Route::post('menu_items/upsert', 'MenuItemsController@upsert');
    Route::resource('menu_items', 'MenuItemsController');

    Route::resource('seos', 'SeoController');
    Route::resource('posts', 'PostController');
    Route::post('/save-draft', 'PostController@save_draft');
    Route::post('/save-history', 'PostController@save_history');
    Route::resource('socials', 'SocialController');
    Route::resource('widgets', 'WidgetController');
    Route::get('widget/setting', 'WidgetController@setting');
});

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v0', 'namespace' => $namespace], function () {
    Route::resource('layouts', 'LayoutController');
    Route::get('layout/status/{path}', 'LayoutController@status');
});

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v0', 'namespace' => $namespace], function () {
    Route::get('/get-districts', 'DefaultController@getAllDistrict');
    Route::post('/store-image', 'DefaultController@storeImage');
    Route::get('/images', 'DefaultController@images');
    Route::get('/delete-image/{id}', 'DefaultController@deleteImage');
});

Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'v0', 'namespace' => $namespace], function () {
    Route::post('xml-store', 'XmlController@store');
    Route::get('xml-restore', 'XmlController@restore');
    Route::post('/run-query/{id}','XmlController@callSchedule');
});

Route::group(['prefix' => 'v0', 'namespace' => $namespace], function () {
    Route::get('/frontend','FrontendController@index');
    Route::get('/frontend/formatted','FrontendController@formatted');
    Route::get('/frontend/header','FrontendController@header');
    //Route::get('/frontend/posts','FrontendController@posts');
    Route::get('/frontend/posts','FrontendController@getAllPost');
    Route::get('/frontend/get-post/{id?}{slug?}','FrontendController@getPost');
    Route::get('/frontend/category-posts/{id}','FrontendController@catPosts');
    // Route::get('/frontend/category-details/{slug}','FrontendController@catDetails');
    Route::get('/frontend/post-titles', 'FrontendController@postTitles');
    Route::get('/frontend/tags', 'FrontendController@getTag');
    Route::get('/frontend/tag-posts/{id}', 'FrontendController@tagPosts');
});
