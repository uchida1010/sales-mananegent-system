<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::where('code', 'admin')->first();
        $officeWokerRole  = Role::where('code', 'office_worker')->first();

        User::all()->each(function ($user) use ($adminRole, $officeWokerRole) {
            $role = $user->id === 1 ? $adminRole : $officeWokerRole;

            $user->roles()->syncWithoutDetaching($role->id);
        });
    }
}
