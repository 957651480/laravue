<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use App\Acl;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
        ]);
        $manager = User::create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make('admin'),
        ]);

        $partner = User::create([
            'name' => 'partner',
            'email' => 'partner@partner.com',
            'password' => Hash::make('admin'),
        ]);

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $partnerRole = Role::findByName(\App\Laravue\Acl::ROLE_PARTNER);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $partner->syncRoles($partnerRole);
    }
}
