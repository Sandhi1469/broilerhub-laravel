<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category' => 'Ayam Hidup Siap Panen'],
            ['category' => 'Karkas Broiler Segar'],
            ['category' => 'Ayam Broiler Parting'],
            ['category' => 'Fillet & Boneless Broiler'],
            ['category' => 'By-Product & Organ Unggas'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['category' => $cat['category']],
                $cat
            );
        }
    }
}
