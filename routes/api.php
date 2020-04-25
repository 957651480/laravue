<?php

use Illuminate\Http\Request;
use \App\Laravue\Faker;
use \App\Laravue\JsonResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/login', 'AuthController@login');
Route::post('auth/wxlogin', 'AuthController@wxLogin');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
    Route::get('users', 'UserController@index')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::post('users', 'UserController@store')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}', 'UserController@show')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::put('users/{user}', 'UserController@update');
    Route::delete('users/{user}', 'UserController@destroy')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_USER_MANAGE);
    Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('roles', 'RoleController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . \App\Laravue\Acl::PERMISSION_PERMISSION_MANAGE);
    Route::post('file/upload', 'FileController@upload');

    //课程路由
    Route::post('courses/create', 'CourseController@store');
    Route::post('courses/update/{id}', 'CourseController@update');
    Route::get('courses/delete/{id}', 'CourseController@destroy');
    Route::get('courses/export', 'CourseController@export');

    Route::apiResource('categories', 'CategoryController');
    Route::apiResource('teachers', 'TeacherController');

    //轮播图路由
    Route::post('banners/create', 'BannerController@store');
    Route::post('banners/update/{id}', 'BannerController@update');
    Route::get('banners/delete/{id}', 'BannerController@destroy');
    //报名列表
    Route::get('attends', 'AttendController@index');
    //报名路由
    Route::post('attends/join/{id}', 'AttendController@join');
    Route::get('attends/delete/{id}', 'AttendController@destroy');
});
//课程路由
Route::get('courses', 'CourseController@index');
Route::get('courses/detail/{id}', 'CourseController@show');

//banner路由
Route::get('banners', 'BannerController@index');
Route::get('banners/detail/{id}', 'BannerController@show');


