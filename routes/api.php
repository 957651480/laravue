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


Route::post('auth/wxlogin', 'AuthController@wxLogin');
//后台路由
Route::prefix('admin/')->namespace('Admin')->group(function ()
{
    //登入和登出
    Route::post('auth/login', 'AuthController@login');


    Route::group(['middleware' => 'auth:api'], function ()
    {
        Route::post('auth/logout', 'AuthController@logout');
        //轮播图
        Route::get('banners', 'BannerController@index');
        Route::post('banners/create', 'BannerController@store');
        Route::get('banners/detail/{id}', 'BannerController@show');
        Route::post('banners/update/{id}', 'BannerController@update');
        Route::get('banners/delete/{id}', 'BannerController@destroy');

        Route::get('auth/user', 'AuthController@user');


    });

});
Route::group(['middleware' => 'auth:api'], function () {

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

    //课程路由
    Route::get('courses/admin/list', 'CourseController@adminIndex');
    Route::get('courses/admin/detail/{id}', 'CourseController@adminShow');
    //我报名的课程
    Route::get('courses/mine', 'CourseController@myCourseList');
    Route::get('courses/mine/detail/{id}', 'CourseController@myCourseDetail');

    //分类路由
    Route::post('categories/create', 'CategoryController@store');
    Route::post('categories/update/{id}', 'CategoryController@update');
    Route::get('categories/delete/{id}', 'CategoryController@destroy');

    //轮播图路由
    Route::post('banners/create', 'BannerController@store');
    Route::post('banners/update/{id}', 'BannerController@update');
    Route::get('banners/delete/{id}', 'BannerController@destroy');
    //教师路由
    Route::apiResource('teachers', 'TeacherController');
    //报名列表
    Route::get('attends', 'AttendController@index');
    //报名路由
    Route::post('attends/join/{id}', 'AttendController@join');
    Route::get('attends/delete/{id}', 'AttendController@destroy');
});
//课程路由
Route::get('courses', 'CourseController@index');
Route::get('courses/detail/{id}', 'CourseController@show');
//分类路由
Route::get('categories','CategoryController@index');
Route::get('categories/detail/{id}','CategoryController@show');
//banner路由
Route::get('banners', 'BannerController@index');
Route::get('banners/detail/{id}', 'BannerController@show');


