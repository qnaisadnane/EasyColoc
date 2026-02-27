<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Alimentation', 'icon' => 'shopping-cart', 'color' => 'green'],
            ['name' => 'Loyer/Charges', 'icon' => 'home', 'color' => 'blue'],
            ['name' => 'Transport', 'icon' => 'truck', 'color' => 'yellow'],
            ['name' => 'Loisirs', 'icon' => 'music', 'color' => 'fuchsia'],
            ['name' => 'Santé', 'icon' => 'heart', 'color' => 'red'],
            ['name' => 'Autre', 'icon' => 'more-horizontal', 'color' => 'slate'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
