<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class RolesAndPermssionswithCreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ask for confirmation to refresh migration
        if ($this->command->confirm('Do you wish to refresh migration before seeding, Make sure it will clear all old data ?')) {
            $this->command->call('migrate:refresh');
            $this->command->warn("Data deleted, starting from fresh database.");
        }
        // Seed the default permissions
//        $permissions = Permission::defaultPermissions();
//        foreach ($permissions as $permission) {
//            Permission::firstOrCreate(['name' => $permission]);
//        }
//        $this->command->info('Default Permissions added.');
        // Ask to confirm to assign admin or user role
        if ($this->command->confirm('Create Roles for admin, default is admin and member? [y|N]', true)) {
            // Explode roles
            $rolesArray = ['admin','member'];
            // add roles
            foreach($rolesArray as $role) {
                $role = Role::firstOrCreate(['name' => trim($role)]);
                if( $role->name == 'admin' ) {
                    // assign all permissions to admin role
                    $role->permissions()->sync(Permission::all());
                    $this->command->info('Admin will have full rights');
                    $this->createUser($role);
                }

            }
            $this->command->info('Roles admin and member added successfully');
        } else {
            Role::firstOrCreate(['name' => 'User']);
            $this->command->info('By default, User role added.');
        }

    }
    /**
     * Create a user with given role
     *
     * @param $role
     */
    private function createUser($role)
    {
        $user = factory(User::class)->create();
        $user->assignRole($role->name);
        if( $role->name == 'admin' ) {
            $this->command->info('Admin login details:');
            $this->command->warn('Username : '.$user->email);
            $this->command->warn('Password : "password"');
        }
    }
}
