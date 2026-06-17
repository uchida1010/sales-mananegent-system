<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'システム管理者', 'code' => 'admin'],
            ['name' => '事務', 'code' => 'office_worker'],
            ['name' => '営業', 'code' => 'sales'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['code' => $role['code']],
                ['name' => $role['name']]
            );
        }
    }
}
