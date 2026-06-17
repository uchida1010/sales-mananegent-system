<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::where('code', 'admin')->first();
        $officeWorker = Role::where('code', 'office_worker')->first();

        $admin->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        $officeWorker->permissions()->sync(
            Permission::whereIn('code', [
                'create_user',
                'edit_user',
            ])->pluck('id')->toArray()
        );
    }
}
