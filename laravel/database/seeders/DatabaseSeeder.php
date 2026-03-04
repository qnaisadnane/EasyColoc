<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create default categories
        \App\Models\Category::create(['name' => 'Loyer', 'color' => '#3b82f6' ]);
        \App\Models\Category::create(['name' => 'Courses', 'color' => '#10b981' ]);
        \App\Models\Category::create(['name' => 'Électricité', 'color' => '#f59e0b']);
        \App\Models\Category::create(['name' => 'Internet', 'color' => '#8b5cf6']);
        \App\Models\Category::create(['name' => 'Eau', 'color' => '#06b6d4']);
        \App\Models\Category::create(['name' => 'Autre', 'color' => '#6b7280']);
    }
}
