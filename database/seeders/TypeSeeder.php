<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Normal',  'color_hex' => '#828282', 'image' => 'normal.png'],
            ['name' => 'Fire',  'color_hex' => '#E4613E', 'image' => 'fire.png'],
            ['name' => 'Water', 'color_hex' => '#3099E1', 'image' => 'water.png'],
            ['name' => 'Grass', 'color_hex' => '#439837', 'image' => 'grass.png'],
            ['name' => 'Electric', 'color_hex' => '#DFBC28', 'image' => 'electric.png'],
            ['name' => 'Ice', 'color_hex' => '#47C8C8', 'image' => 'ice.png'],
            ['name' => 'Fighting', 'color_hex' => '#E49021', 'image' => 'fighting.png'],
            ['name' => 'Poison', 'color_hex' => '#9354CB', 'image' => 'poison.png'],
            ['name' => 'Ground', 'color_hex' => '#A4733C', 'image' => 'ground.png'],
            ['name' => 'Flying', 'color_hex' => '#74AAD0', 'image' => 'flying.png'],
            ['name' => 'Psychic', 'color_hex' => '#E96C8D', 'image' => 'psychic.png'],
            ['name' => 'Bug', 'color_hex' => '#9F9F28', 'image' => 'bug.png'],
            ['name' => 'Rock', 'color_hex' => '#A9A481', 'image' => 'rock.png'],
            ['name' => 'Ghost', 'color_hex' => '#6F4570', 'image' => 'ghost.png'],
            ['name' => 'Dragon', 'color_hex' => '#576FBC', 'image' => 'dragon.png'],
            ['name' => 'Dark',  'color_hex' => '#4F4747',  'image' =>  "dark.png"],
            ['name' => "Steel",  "color_hex"  =>  "#74B0CB",  "image"  =>  "steel.png"],
            ['name' => "Fairy",  "color_hex"  =>  "#E18CE1",  "image"  =>  "fairy.png"],
        ];

        foreach ($types as $type) {
            $sourcePath = database_path('seeders/images/' . $type['image']);
            $storedPath = $type['image']; // relatif terhadap disk 'public'

            // copy file dari folder sumber ke storage/app/public/types/
            if (file_exists($sourcePath)) {
                Storage::disk('public')->put($storedPath, file_get_contents($sourcePath));
            }

            Type::create([
                'name'         => $type['name'],
                'color_hex'    => $type['color_hex'],
                'sprite_image' => $storedPath, // disimpan: "types/fire.png"
            ]);
        }
    }
}
