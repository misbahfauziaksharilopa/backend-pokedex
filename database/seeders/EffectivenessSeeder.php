<?php

namespace Database\Seeders;

use App\Models\Effectiveness;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EffectivenessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chart = [
            'Normal'   => ['weak' => [], 'resist' => ['Rock', 'Steel'], 'immune' => ['Ghost']],
            'Fire'     => ['weak' => ['Grass', 'Ice', 'Bug', 'Steel'], 'resist' => ['Fire', 'Water', 'Rock', 'Dragon'],'immune' => []],
            'Water'    => ['weak' => ['Fire', 'Ground', 'Rock'], 'resist' => ['Water', 'Grass', 'Dragon'], 'immune' => []],
            'Electric' => ['weak' => ['Water', 'Flying'], 'resist' => ['Electric', 'Grass', 'Dragon'], 'immune' => ['Ground']],
            'Grass'    => ['weak' => ['Water', 'Ground', 'Rock'], 'resist' => ['Fire', 'Grass', 'Poison', 'Flying', 'Bug', 'Dragon', 'Steel'], 'immune' => []],
            'Ice'      => ['weak' => ['Grass', 'Ground', 'Flying', 'Dragon'], 'resist' => ['Fire', 'Water', 'Ice', 'Steel'], 'immune' => []],
            'Fighting' => ['weak' => ['Normal', 'Ice', 'Rock', 'Dark', 'Steel'], 'resist' => ['Poison', 'Flying', 'Psychic', 'Bug', 'Fairy'], 'immune' => ['Ghost']],
            'Poison'   => ['weak' => ['Grass', 'Fairy'], 'resist' => ['Poison', 'Ground', 'Rock', 'Ghost'], 'immune' => ['Steel']],
            'Ground'   => ['weak' => ['Fire', 'Electric', 'Poison', 'Rock', 'Steel'], 'resist' => ['Grass', 'Bug'], 'immune' => ['Flying']],
            'Flying'   => ['weak' => ['Grass', 'Fighting', 'Bug'], 'resist' => ['Electric', 'Rock', 'Steel'], 'immune' => []],
            'Psychic'  => ['weak' => ['Fighting', 'Poison'], 'resist' => ['Psychic', 'Steel'], 'immune' => ['Dark']],
            'Bug'      => ['weak' => ['Grass', 'Psychic', 'Dark'], 'resist' => ['Fire', 'Fighting', 'Poison', 'Flying', 'Ghost', 'Steel', 'Fairy'], 'immune' => []],
            'Rock'     => ['weak' => ['Fire', 'Ice', 'Flying', 'Bug'], 'resist' => ['Fighting', 'Ground', 'Steel'], 'immune' => []],
            'Ghost'    => ['weak' => ['Psychic', 'Ghost'], 'resist' => ['Dark'], 'immune' => ['Normal']],
            'Dragon'   => ['weak' => ['Dragon'], 'resist' => ['Steel'], 'immune' => ['Fairy']],
            'Dark'     => ['weak' => ['Psychic', 'Ghost'], 'resist' => ['Fighting', 'Dark', 'Fairy'], 'immune' => []],
            'Steel'    => ['weak' => ['Ice', 'Rock', 'Fairy'], 'resist' => ['Fire', 'Water', 'Electric', 'Steel'], 'immune' => []],
            'Fairy'    => ['weak' => ['Fighting', 'Dragon', 'Dark'], 'resist' => ['Fire', 'Poison', 'Steel'], 'immune' => []],
        ];

        $typeIds = Type::pluck('id', 'name'); // ["Fire" => 2, "Water" => 3, ...]

        foreach ($chart as $attackingName => $rules) {
            $attackingId = $typeIds[$attackingName] ?? null;

            if (! $attackingId) {
                $this->command->warn("Tipe '{$attackingName}' tidak ditemukan — cek ejaan di TypeSeeder.");
                continue;
            }

            foreach ($typeIds as $defendingName => $defendingId) {
                $category = match (true) {
                    in_array($defendingName, $rules['immune']) => 'immune',
                    in_array($defendingName, $rules['resist']) => 'resist',
                    in_array($defendingName, $rules['weak'])   => 'weak',
                    default                                     => 'normal',
                };

                Effectiveness::create([
                    'attacking_type_id' => $attackingId,
                    'defending_type_id' => $defendingId,
                    'category'          => $category,
                ]);
            }
        }
    }
}
