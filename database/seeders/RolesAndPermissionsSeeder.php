<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Default User Role
        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions(Permission::all());

        // Admin Role
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // Organization Role
        $organization = Role::firstOrCreate(['name' => 'organization']);
        $organization->syncPermissions(Permission::all());

        // Organization Admin Role
        $organizationAdmin = Role::firstOrCreate(['name' => 'organization_admin']);
        $organizationAdmin->syncPermissions(Permission::all());

        // Organization User Role
        $organizationUser = Role::firstOrCreate(['name' => 'organization_user']);
        $organizationUser->syncPermissions(Permission::all());
    }
}
