<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {  
        
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        
        $permissions = [
            'manage books',
            'manage categories',
            'manage borrowings',
            'manage reviews',
            'view reports',
            'manage users',
            'manage roles & permissions',
        ];

        foreach ($permissions as $permission) {
           Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);
        $subscriber = Role::firstOrCreate(['name' => 'subscriber','guard_name' => 'web']);

       
        $admin->givePermissionTo(Permission::all());
        
        
        $employee->givePermissionTo([
             'manage books',
             'manage categories',
             'manage borrowings',
             'manage reviews',
        ]);

    }   
}
