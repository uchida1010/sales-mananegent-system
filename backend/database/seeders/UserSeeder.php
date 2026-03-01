<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

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
            'user_code' => 0,
            'status' => fake()->randomElement($status),
            'email' => 'admin@example.com',
            'joined_at' => '2000-01-01',
        ]);

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        User::factory(5)->create()->each(function ($user) use ($officeWorkerRole) {
            $user->roles()->syncWithoutDetaching([$officeWorkerRole->id]);
        });
    }
}
