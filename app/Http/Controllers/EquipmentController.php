<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Period;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EquipmentController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        Gate::authorize('create-equipment');

        return inertia('Equipment/Form')->with([
            'periods' => Period::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create-equipment');

        $validated = $request->validate([
            'description' => ['required'],
            'in_stock' => ['integer'],
            'effective_qty' => ['integer'],
            'min_qty' => ['integer'],
            'supplier_id' => ['nullable', 'exists:App\Models\Supplier,id'],
            'values.*.period_id' => ['required', 'exists:App\Models\Period,id'],
            'values.*.value' => ['required', 'numeric'],
        ]);

        $equipment = Equipment::create($validated);

        if (isset($validated['values'])) {
            $equipment->values()->createMany($validated['values']);
        }

        return redirect()
            ->route('equipments.index')
            ->with('flash', 'Equipamento cadastrado');
    }
}
