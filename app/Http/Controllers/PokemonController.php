<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Effectiveness;
use App\Models\Pokemon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PokemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $pokemons = Pokemon::with(['type1', 'type2'])
        ->when($search, function ($q, $search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('national_number', 'like', "%{$search}%");
        })
        ->orderBy('national_number')
        ->paginate(20);

        $allEffectiveness = Effectiveness::with('attackingType')->get();

        $pokemons->getCollection()->transform(function ($pokemon) use ($allEffectiveness) {
            $pokemon->weaknesses = $this->calculateDualTypeEffectiveness(
                $pokemon->type_1_id,
                $pokemon->type_2_id,
                $allEffectiveness
            );
            return $pokemon;
        });

        return response()->json($pokemons);
    }

    private function validationRules(?int $ignoreId = null): array
    {
        return [
            'national_number' => ['required', 'string', 'max:255', Rule::unique('pokemon', 'national_number')->ignore($ignoreId)],
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'type_1_id'        => 'required|integer|exists:types,id',
            'type_2_id'        => ['nullable', 'integer', 'exists:types,id', Rule::notIn([request('type_1_id')])],
            'ability_1'        => 'nullable|string|max:255',
            'ability_2'        => 'nullable|string|max:255',
            'hidden_ability'   => 'nullable|string|max:255',
            'sprite_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'form_1_id'        => 'nullable|integer|exists:forms,id',
            'form_2_id'        => 'nullable|integer|exists:forms,id',
            'form_3_id'        => 'nullable|integer|exists:forms,id',
            'hp'  => 'required|integer|min:0|max:65535',
            'atk' => 'required|integer|min:0|max:65535',
            'def' => 'required|integer|min:0|max:65535',
            'spa' => 'required|integer|min:0|max:65535',
            'spd' => 'required|integer|min:0|max:65535',
            'spe' => 'required|integer|min:0|max:65535',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validationRules());

        if ($request->hasFile('sprite_image')) {
            $uploadedUrl = \Cloudinary::upload(
                $request->file('sprite_image')->getRealPath(),
                ['folder' => 'pokedex/pokemon']
            )->getSecurePath();
            $validated['sprite_image'] = $uploadedUrl;
        }

        $pokemon = Pokemon::create($validated);
        $pokemon->load('type1', 'type2');

        return response()->json($pokemon, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pokemon $pokemon)
    {
        $pokemon->load([
            'type1', 'type2',
            'form1.type1', 'form1.type2',
            'form2.type1', 'form2.type2',
            'form3.type1', 'form3.type2',
        ]);

        $allEffectiveness = Effectiveness::with('attackingType')->get();

        foreach (['form1', 'form2', 'form3'] as $formKey) {
            if ($pokemon->$formKey) {
                $form = $pokemon->$formKey;
                $form->weaknesses = $this->calculateDualTypeEffectiveness(
                    $form->type_1_id,
                    $form->type_2_id,
                    $allEffectiveness
                );  
            }
        }

        $response = $pokemon->toArray();
        $response['weaknesses'] = $this->calculateDualTypeEffectiveness(
            $pokemon->type_1_id,
            $pokemon->type_2_id,
            $allEffectiveness
        );

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pokemon $pokemon)
    {
        $validated = $request->validate($this->validationRules($pokemon->id));

        if ($request->hasFile('sprite_image')) {
            $uploadedUrl = \Cloudinary::upload(
                $request->file('sprite_image')->getRealPath(),
                ['folder' => 'pokedex/pokemon']
            )->getSecurePath();
            $validated['sprite_image'] = $uploadedUrl;
        }

        $pokemon->update($validated);
        $pokemon->load('type1', 'type2');

        return response()->json($pokemon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pokemon $pokemon)
    {
        $pokemon->delete();
        return response()->json(null, 204);
    }

    private function calculateDualTypeEffectiveness(int $type1Id, ?int $type2Id, $effectivenessRows): array
    {
        $multiplierMap = ['immune' => 0, 'resist' => 0.5, 'normal' => 1, 'weak' => 2];
        $defendingIds  = array_filter([$type1Id, $type2Id]);

        $rows = $effectivenessRows
            ->whereIn('defending_type_id', $defendingIds)
            ->groupBy('attacking_type_id'); 

        $result = [];

        foreach ($rows as $attackingTypeId => $group) {
            $combined = $group->reduce(
                fn ($carry, $row) => $carry * $multiplierMap[$row->category],
                1
            );

            $result[] = [
                'attacking_type_id'   => $attackingTypeId,
                'attacking_type_name' => $group->first()->attackingType->name,
                'multiplier'          => $combined,
                'label'               => match (true) {
                    $combined == 0 => 'immune',
                    $combined < 1  => 'resist',
                    $combined == 1 => 'normal',
                    default        => 'weak',
                },
            ];
        }

        usort($result, fn ($a, $b) => $b['multiplier'] <=> $a['multiplier']);

        return $result;
    }
}
