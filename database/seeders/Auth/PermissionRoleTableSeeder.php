<?php

namespace Database\Seeders\Auth;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

/**
 * Class PermissionRoleTableSeeder.
 */
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $admin = Role::firstOrCreate(['name' => 'admin', 'title' => 'Admin', 'is_fixed' => true]);
        $demo_admin = Role::firstOrCreate(['name' => 'demo_admin', 'title' => 'Demo Admin', 'is_fixed' => true]);
        $user = Role::firstOrCreate(['name' => 'user', 'title' => 'user', 'is_fixed' => true]);
        $producer = Role::firstOrCreate(['name' => 'producer', 'title' => 'Producer', 'is_fixed' => true]);

        Permission::firstOrCreate(['name' => 'edit_settings', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'view_logs', 'is_fixed' => true]);

        $modules = config('constant.MODULES');

        foreach ($modules as $key => $module) {
            $permissions = ['view', 'add', 'edit', 'delete', 'restore' ,'force_delete'];
            $module_name = strtolower(str_replace(' ', '_', $module['module_name']));
            foreach ($permissions as $key => $value) {
                $permission_name = $value.'_'.$module_name;
                Permission::firstOrCreate(['name' => $permission_name, 'is_fixed' => true]);
            }
            if (isset($module['more_permission']) && is_array($module['more_permission'])) {
                foreach ($module['more_permission'] as $key => $value) {
                    $permission_name = $module_name.'_'.$value;
                    Permission::firstOrCreate(['name' => $permission_name, 'is_fixed' => true]);
                }
            }

            if ($module['module_name'] === 'Clinic Categories') {
                $permission_name = 'view_' . $module_name;
                Permission::firstOrCreate(['name' => $permission_name, 'is_fixed' => true]);
            }
        }

        // Producer specific permissions
        Permission::firstOrCreate(['name' => 'upload_movie', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'manage_movies', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'view_earnings', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'request_payout', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'manage_profile', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'view_analytics', 'is_fixed' => true]);
        Permission::firstOrCreate(['name' => 'manage_payment_settings', 'is_fixed' => true]);

        // Assign producer permissions
        $producer->givePermissionTo([
            'upload_movie',
            'manage_movies',
            'view_earnings',
            'request_payout',
            'manage_profile',
            'view_analytics',
            'manage_payment_settings'
        ]);

        // Assign Permissions to Roles
        $admin->givePermissionTo(Permission::get());

        $demo_admin->givePermissionTo(Permission::get());

        Schema::enableForeignKeyConstraints();
    }
}
