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
            ['name' => 'Alimentation', 'color' => 'green'],
            ['name' => 'Loyer', 'color' => 'blue'],
            ['name' => 'Internet', 'color' => 'orange'],
            ['name' => 'Eau/Electrecite', 'color' => 'yellow'],
            ['name' => 'Loisirs', 'color' => 'fuchsia'],
            ['name' => 'Nettoyage', 'color' => 'red'],
            ['name' => 'Autres', 'color' => 'slate'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
