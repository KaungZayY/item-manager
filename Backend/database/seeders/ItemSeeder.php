<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::factory(5)->create(['category_id' => 1]);
        Item::factory(5)->create(['category_id' => 2]);
        Item::factory(5)->create(['category_id' => 3]);
    }
}
