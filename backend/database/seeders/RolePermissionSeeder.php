<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

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
