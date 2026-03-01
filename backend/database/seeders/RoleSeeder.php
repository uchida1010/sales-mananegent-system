<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

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
