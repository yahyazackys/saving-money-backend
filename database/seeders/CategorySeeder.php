<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create(['name' => 'Food']);
        Category::create(['name' => 'Transportation']);
        Category::create(['name' => 'Entertainment']);
        Category::create(['name' => 'Utilities']);
        Category::create(['name' => 'Education']);
    }
}
