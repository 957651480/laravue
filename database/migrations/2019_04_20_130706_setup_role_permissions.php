<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Laravue\Models\Role;
use App\Laravue\Models\Permission;
use App\Laravue\Acl;

class SetupRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            ['name'=>'admin','display_name'=>'超级管理员','guard_name'=>'api'],
            ['name'=>'manager','display_name'=>'管理员','guard_name'=>'api'],
            ['name'=>'editor','display_name'=>'编辑员','guard_name'=>'api'],
            ['name'=>'user','display_name'=>'用户','guard_name'=>'api'],
            ['name'=>'visitor','display_name'=>'合伙人','guard_name'=>'api'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name'=>$role['name']],$role);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $userRole = Role::findByName(Acl::ROLE_USER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);

        $permissions =[
            ['name'=>'view menu element ui','display_name'=>'查看elementUi','guard_name'=>'api'],
            ['name'=>'view menu permission','display_name'=>'查看权限','guard_name'=>'api'],
            ['name'=>'view menu components','display_name'=>'查看组件','guard_name'=>'api'],
            ['name'=>'view menu charts','display_name'=>'查看Echats','guard_name'=>'api'],
            ['name'=>'view menu nested routes','display_name'=>'查看嵌套路由','guard_name'=>'api'],
            ['name'=>'view menu table','display_name'=>'查看table','guard_name'=>'api'],
            ['name'=>'view menu administrator','display_name'=>'查看管理员','guard_name'=>'api'],
            ['name'=>'view menu theme','display_name'=>'查看主题','guard_name'=>'api'],
            ['name'=>'view menu clipboard','display_name'=>'查看主题','guard_name'=>'api'],
            ['name'=>'view menu excel','display_name'=>'查看主题','guard_name'=>'api'],
            ['name'=>'view menu zip','display_name'=>'查看主题','guard_name'=>'api'],
            ['name'=>'view menu pdf','display_name'=>'查看主题','guard_name'=>'api'],
            ['name'=>'view menu i18n','display_name'=>'查看I18N','guard_name'=>'api'],

            ['name'=>'manage user','display_name'=>'显示名','guard_name'=>'api'],
            ['name'=>'manage article','display_name'=>'显示名','guard_name'=>'api'],
            ['name'=>'manage permission','display_name'=>'显示名','guard_name'=>'api'],
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name'=>$permission['name']],$permission);
        }

        // Setup basic permission
        $adminRole->givePermissionTo(Acl::permissions());
        $managerRole->givePermissionTo(Acl::permissions([Acl::PERMISSION_PERMISSION_MANAGE]));
        $editorRole->givePermissionTo(Acl::menuPermissions());
        $editorRole->givePermissionTo(Acl::PERMISSION_ARTICLE_MANAGE);
        $userRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);
        $visitorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);

        foreach (Acl::roles() as $role) {
            /** @var \App\User[] $users */
            $users = \App\Laravue\Models\User::where('role', $role)->get();
            $role = Role::findByName($role);
            foreach ($users as $user) {
                $user->syncRoles($role);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('editor');
            });
        }

        /** @var \App\User[] $users */
        $users = \App\Laravue\Models\User::all();
        foreach ($users as $user) {
            $roles = array_reverse(Acl::roles());
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $user->role = $role;
                    $user->save();
                }
            }
        }
    }
}
