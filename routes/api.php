<?php


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


Route::post('wechat/login', 'WechatController@login');
Route::any('wechat/notify', 'WechatController@notify');
Route::any('wechat/order', 'WechatController@order');
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
        Route::get('region/tree_list', 'RegionController@treeList');
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

        //资讯管理
        Route::get('information', 'InformationController@index');
        Route::post('information/create', 'InformationController@store');
        Route::get('information/detail/{id}', 'InformationController@show');
        Route::post('information/update/{id}', 'InformationController@update');
        Route::get('information/delete/{id}', 'InformationController@destroy');

        //转盘路由
        Route::get('lottery', 'LotteryController@index');
        Route::post('lottery/create', 'LotteryController@store');
        Route::get('lottery/detail/{id}', 'LotteryController@show');
        Route::post('lottery/update/{id}', 'LotteryController@update');
        Route::get('lottery/delete/{id}', 'LotteryController@destroy');

        Route::get('lottery/prize', 'LotteryPrizeController@index');
        Route::post('lottery/prize/create', 'LotteryPrizeController@store');
        Route::get('lottery/prize/detail/{id}', 'LotteryPrizeController@show');
        Route::post('lottery/prize/update/{id}', 'LotteryPrizeController@update');
        Route::get('lottery/prize/delete/{id}', 'LotteryPrizeController@destroy');

        Route::get('lottery/record', 'LotteryPrizeController@index');
        //竞拍路由
        Route::get('auction', 'AuctionController@index');
        Route::post('auction/create', 'AuctionController@store');
        Route::get('auction/detail/{id}', 'AuctionController@show');
        Route::post('auction/update/{id}', 'AuctionController@update');
        Route::get('auction/delete/{id}', 'AuctionController@destroy');
        //车位
        Route::get('parking', 'ParkingController@index');
        Route::post('parking/create', 'ParkingController@store');
        Route::get('parking/detail/{id}', 'ParkingController@show');
        Route::post('parking/update/{id}', 'ParkingController@update');
        Route::get('parking/delete/{id}', 'ParkingController@destroy');

        //车位区域
        Route::get('parking/area', 'ParkingAreaController@index');
        Route::post('parking/area/create', 'ParkingAreaController@store');
        Route::get('parking/area/detail/{id}', 'ParkingAreaController@show');
        Route::post('parking/area/update/{id}', 'ParkingAreaController@update');
        Route::get('parking/area/delete/{id}', 'ParkingAreaController@destroy');

        //车位楼层
        Route::get('parking/floor', 'ParkingFloorController@index');
        Route::post('parking/floor/create', 'ParkingFloorController@store');
        Route::get('parking/floor/detail/{id}', 'ParkingFloorController@show');
        Route::post('parking/floor/update/{id}', 'ParkingFloorController@update');
        Route::get('parking/floor/delete/{id}', 'ParkingFloorController@destroy');

        Route::get('auth/user', 'AuthController@user');
        Route::get('users', 'UserController@index')->middleware('permission:' . \App\Models\Acl::PERMISSION_USER_MANAGE);
        Route::post('users', 'UserController@store')->middleware('permission:' . \App\Models\Acl::PERMISSION_USER_MANAGE);
        Route::get('users/{user}', 'UserController@show')->middleware('permission:' . \App\Models\Acl::PERMISSION_USER_MANAGE);
        Route::put('users/{user}', 'UserController@update');
        Route::delete('users/{user}', 'UserController@destroy')->middleware('permission:' . \App\Models\Acl::PERMISSION_USER_MANAGE);
        Route::get('users/{user}/permissions', 'UserController@permissions')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);
        Route::put('users/{user}/permissions', 'UserController@updatePermissions')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);

        Route::post('roles/create','RoleController@store')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);
        Route::post('roles/update/{id}', 'RoleController@update')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('roles', 'RoleController')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);
        Route::get('roles/{role}/permissions', 'RoleController@permissions')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . \App\Models\Acl::PERMISSION_PERMISSION_MANAGE);

        Route::post('file/upload', 'FileController@upload');
    });

});
Route::group(['middleware' => 'auth:api'], function () {


    //我报名的课程
    Route::get('courses/mine', 'CourseController@myCourseList');
    Route::get('courses/mine/detail/{id}', 'CourseController@myCourseDetail');

    //分类路由
    Route::post('categories/create', 'CategoryController@store');
    Route::post('categories/update/{id}', 'CategoryController@update');
    Route::get('categories/delete/{id}', 'CategoryController@destroy');

    //教师路由
    Route::apiResource('teachers', 'TeacherController');
    //报名列表
    Route::get('attends', 'AttendController@index');
    //报名路由
    Route::post('attends/join/{id}', 'AttendController@join');
    Route::get('attends/delete/{id}', 'AttendController@destroy');
});

//公共路由
Route::namespace('Common')->group(function (){

    Route::get('region/city','RegionController@city');
    Route::get('house/list','HouseController@index');
    Route::get('house/detail/{id}','HouseController@detail');
});

//分类路由
Route::get('categories','CategoryController@index');
Route::get('categories/detail/{id}','CategoryController@show');
//banner路由
Route::get('banners', 'BannerController@index');
Route::get('banners/detail/{id}', 'BannerController@show');


