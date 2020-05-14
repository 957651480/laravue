<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Acl;

class SetupRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_permissions = config('role-permission');
        $roles =$role_permissions['roles'];
        $permissions =$role_permissions['permissions'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name'=>$role['name']],$role);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);

        $partnerRole = Role::findByName(Acl::ROLE_PARTNER);

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name'=>$permission['name']],$permission);
        }

        // Setup basic permission
        $adminRole->givePermissionTo(Permission::all());
        $managerRole->givePermissionTo(Permission::all());
        $partnerRole->givePermissionTo(Permission::whereIn('name',[
            'view menu house ui',
            'view menu parking ui',
            'view menu region ui',
            'view menu lottery ui',
            'view menu auction ui',
            'view menu information ui',
            'view menu banner ui',
        ])->get());

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
