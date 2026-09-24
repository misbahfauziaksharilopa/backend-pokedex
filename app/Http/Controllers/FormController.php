<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $forms = Form::with(['type1', 'type2'])
            ->when($search, fn ($q, $search) => $q->where('form_name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10);

        return response()->json($forms);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'form_name'      => 'required|string|max:255',
            'type_1_id'      => 'required|integer|exists:types,id',
            'type_2_id'      => ['nullable', 'integer', 'exists:types,id', Rule::notIn([$request->type_1_id])],
            'ability_1'      => 'nullable|string|max:255',
            'ability_2'      => 'nullable|string|max:255',
            'hidden_ability' => 'nullable|string|max:255',
            'sprite_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'hp'  => 'required|integer|min:0|max:65535',
            'atk' => 'required|integer|min:0|max:65535',
            'def' => 'required|integer|min:0|max:65535',
            'spa' => 'required|integer|min:0|max:65535',
            'spd' => 'required|integer|min:0|max:65535',
            'spe' => 'required|integer|min:0|max:65535',
        ]);

        if ($request->hasFile('sprite_image')) {
            $uploadedUrl = \Cloudinary::upload(
                $request->file('sprite_image')->getRealPath(),
                ['folder' => 'pokedex/forms']
            )->getSecurePath();
            $validated['sprite_image'] = $uploadedUrl;
        }

        $form = Form::create($validated);
        $form->load('type1', 'type2');

        return response()->json($form, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        $form->load('type1', 'type2');
        return response()->json($form);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        $validated = $request->validate([
            'form_name'      => 'required|string|max:255',
            'type_1_id'      => 'required|integer|exists:types,id',
            'type_2_id'      => ['nullable', 'integer', 'exists:types,id', Rule::notIn([$request->type_1_id])],
            'ability_1'      => 'nullable|string|max:255',
            'ability_2'      => 'nullable|string|max:255',
            'hidden_ability' => 'nullable|string|max:255',
            'sprite_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'hp'  => 'required|integer|min:0|max:65535',
            'atk' => 'required|integer|min:0|max:65535',
            'def' => 'required|integer|min:0|max:65535',
            'spa' => 'required|integer|min:0|max:65535',
            'spd' => 'required|integer|min:0|max:65535',
            'spe' => 'required|integer|min:0|max:65535',
        ]);

        if ($request->hasFile('sprite_image')) {
            $uploadedUrl = \Cloudinary::upload(
                $request->file('sprite_image')->getRealPath(),
                ['folder' => 'pokedex/forms']
            )->getSecurePath();
            $validated['sprite_image'] = $uploadedUrl;
        }

        $form->update($validated);
        $form->load('type1', 'type2');

        return response()->json($form);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        $form->delete();
        return response()->json(null, 204);
    }
}
