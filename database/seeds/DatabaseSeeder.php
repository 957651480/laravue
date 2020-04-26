<?php

use App\Laravue\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Laravue\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
       /* $manager = User::create([
            'name' => 'manager',
            'email' => 'manager@manager.com',
            'password' => Hash::make('admin'),
        ]);
        $editor = User::create([
            'name' => 'editor',
            'email' => 'editor@editor.com',
            'password' => Hash::make('admin'),
        ]);
        $user = User::create([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('admin'),
        ]);
        $visitor = User::create([
            'name' => 'visitor',
            'email' => 'visitor@visitor.com',
            'password' => Hash::make('admin'),
        ]);*/

        $adminRole = Role::findByName(\App\Laravue\Acl::ROLE_ADMIN);
       /* $managerRole = Role::findByName(\App\Laravue\Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(\App\Laravue\Acl::ROLE_EDITOR);
        $userRole = Role::findByName(\App\Laravue\Acl::ROLE_USER);
        $visitorRole = Role::findByName(\App\Laravue\Acl::ROLE_VISITOR);*/
        $admin->syncRoles($adminRole);
       /* $manager->syncRoles($managerRole);
        $editor->syncRoles($editorRole);
        $user->syncRoles($userRole);
        $visitor->syncRoles($visitorRole);*/
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
    }
}
