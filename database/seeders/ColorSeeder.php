<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Color;

class ColorSeeder extends Seeder
{
    public function run()
    {
        $colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00', '#FF00FF', '#C0C0C0', '#000080', '#808000'];

        foreach ($colors as $color) {
            Color::create([
                'color' => $color
            ]);
        }
    }
}
