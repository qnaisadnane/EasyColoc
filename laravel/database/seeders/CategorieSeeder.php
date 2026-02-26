<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categorie::insert([
            'name'=>'Eau/Electrecité',
            'name'=>'wifi',
            'name'=>'loyer',
            'name'=>'courses',
            'name'=>'loisir',
        ]);
    }
}
