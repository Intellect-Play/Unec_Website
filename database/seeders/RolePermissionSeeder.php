<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // //
        // // Super Admin rolünü oluştur
        // $superAdmin = Role::create(['name' => 'super_admin']);

        // // Bazı izinleri oluştur
        // $permissions = ['create_user', 'delete_user', 'edit_user', 'assign_roles'];
        // foreach ($permissions as $perm) {
        //     Permission::create(['name' => $perm]);
        // }

        // // Hepsini super admin rolüne ata
        // $superAdmin->permissions()->attach(Permission::pluck('id'));

        // // İlk admin user’a super admin rolü ata
        // AdminUser::create([
        //     'name' => 'Root',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('coxcetinadminparolu57'),
        //     'role_id' => $superAdmin->id
        // ]);


        // Super Admin rolünü oluştur
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

        // Tüm gerekli izinleri oluştur
        $permissions = [
            'create_event',
            'edit_event',
            'delete_event',
            'view_event',
            'create_user',
            'delete_user',
            'edit_user',
            'assign_roles',
            'view_user',
            'create_post',
            'edit_post',
            'delete_post',
            'view_post',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Tüm izinleri bu role ver
        $allPermissionIds = Permission::pluck('id')->toArray();
        $superAdmin->permissions()->sync($allPermissionIds);

        // İlk admin user’a super admin rolü ata
        AdminUser::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Root',
            'password' => bcrypt('coxcetinadminparolu57'),
            'role_id' => $superAdmin->id,
        ]);
    }
}
