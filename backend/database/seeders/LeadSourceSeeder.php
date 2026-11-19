<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    public function run(): void
    {
        $leadSources = [
            'HP',
            '紹介',
            '営業',
        ];

        foreach ($leadSources as $index => $name) {
            LeadSource::create([
                'name' => $name,
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
