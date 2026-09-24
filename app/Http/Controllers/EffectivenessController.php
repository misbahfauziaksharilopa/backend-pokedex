<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Effectiveness;
use Illuminate\Http\Request;

class EffectivenessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attackingTypeId = $request->query('attacking_type_id');
        $defendingTypeId = $request->query('defending_type_id');

        $effectiveness = Effectiveness::with(['attackingType', 'defendingType'])
            ->when($attackingTypeId, fn ($q, $id) => $q->where('attacking_type_id', $id))
            ->when($defendingTypeId, fn ($q, $id) => $q->where('defending_type_id', $id))
            ->get();

        return response()->json($effectiveness);
    }

    /**
     * Display the specified resource.
     */
    public function show(Effectiveness $effectiveness)
    {
        $effectiveness->load('attackingType', 'defendingType');
        return response()->json($effectiveness);
    }
}
