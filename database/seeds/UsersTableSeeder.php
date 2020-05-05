<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Acl;
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
            'city_id' => 2,
            'password' => Hash::make('admin'),
        ]);

        $demo = User::create([
            'name' => 'demo',
            'email' => 'demo@demo.com',
            'city_id' => 1828,
            'password' => Hash::make('admin'),
        ]);
        $adminRole = Role::findByName(\App\Models\Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(\App\Models\Acl::ROLE_MANAGER);
        $partnerRole = Role::findByName(\App\Models\Acl::ROLE_PARTNER);
        $admin->syncRoles($adminRole);
        $manager->syncRoles($managerRole);
        $partner->syncRoles($partnerRole);
        $demo->syncRoles($partnerRole);
    }
}
