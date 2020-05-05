<?php
return [

    'roles'=>[
        ['name'=>'admin','display_name'=>'超级管理员','guard_name'=>'api'],
        ['name'=>'manager','display_name'=>'管理员','guard_name'=>'api'],
        ['name'=>'partner','display_name'=>'合伙人','guard_name'=>'api'],
    ],
    'permissions'=>[
        ['name'=>'view menu region ui','display_name'=>'地区列表','guard_name'=>'api'],
        ['name'=>'view menu banner ui','display_name'=>'首页轮播图显示','guard_name'=>'api'],
        ['name'=>'view menu banner ui','display_name'=>'首页轮播图显示','guard_name'=>'api'],
        ['name'=>'view menu administrator','display_name'=>'查看权限','guard_name'=>'api'],
        ['name'=>'view menu permission','display_name'=>'查看权限','guard_name'=>'api'],

        ['name'=>'manage user','display_name'=>'管理用户','guard_name'=>'api'],
        ['name'=>'manage article','display_name'=>'管理文章','guard_name'=>'api'],
        ['name'=>'manage permission','display_name'=>'管理权限','guard_name'=>'api'],
    ]
];
