<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::where('code', 'admin')->first();
        $officeWokerRole = Role::where('code', 'office_worker')->first();

        User::all()->each(function ($user) use ($adminRole, $officeWokerRole) {
            $role = $user->id === 1 ? $adminRole : $officeWokerRole;

            $user->roles()->syncWithoutDetaching($role->id);
        });
    }
}
