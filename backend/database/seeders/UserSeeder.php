<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminRole = Role::where('code', 'admin')->firstOrFail();
        $officeWorkerRole = Role::where('code', 'office_worker')->firstOrFail();

        $status = ['active', 'leave', 'resigned'];

        $admin = User::factory()->create([
            'user_code' => 'E000',
            'status' => fake()->randomElement($status),
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'joined_at' => '2000-01-01',
        ]);

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        User::factory(40)->create()->each(function ($user) use ($officeWorkerRole) {
            $user->roles()->syncWithoutDetaching([$officeWorkerRole->id]);
        });
    }
}
