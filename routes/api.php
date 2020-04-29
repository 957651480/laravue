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
        Route::get('banner', 'BannerController@index');
        Route::post('banner/create', 'BannerController@store');
        Route::get('banner/detail/{id}', 'BannerController@show');
        Route::post('banner/update/{id}', 'BannerController@update');
        Route::get('banner/delete/{id}', 'BannerController@destroy');

        //城市管理路由
        Route::get('region', 'RegionController@index');
        Route::post('region/create', 'RegionController@store');
        Route::get('region/detail/{id}', 'RegionController@show');
        Route::post('region/update/{id}', 'RegionController@update');
        Route::get('region/delete/{id}', 'RegionController@destroy');

        //楼盘路由
        Route::get('house', 'HouseController@index');
        Route::post('house/create', 'HouseController@store');
        Route::get('house/detail/{id}', 'HouseController@show');
        Route::post('house/update/{id}', 'HouseController@update');
        Route::get('house/delete/{id}', 'HouseController@destroy');
        Route::get('house/export', 'HouseController@export');

        //教师路由
        Route::apiResource('teachers', 'TeacherController');

        Route::get('auth/user', 'AuthController@user');
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

    });

});
Route::group(['middleware' => 'auth:api'], function () {


    Route::post('file/upload', 'FileController@upload');




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

//分类路由
Route::get('categories','CategoryController@index');
Route::get('categories/detail/{id}','CategoryController@show');
//banner路由
Route::get('banners', 'BannerController@index');
Route::get('banners/detail/{id}', 'BannerController@show');


