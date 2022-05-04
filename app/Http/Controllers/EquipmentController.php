<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Filters;
use App\Models\Period;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EquipmentController extends Controller
{
    public function index(Filters $filters)
    {
        Gate::authorize('view-equipments');

        $equipments = $filters
            ->like('description', 'description')
            ->equals('supplier', 'supplier_id')
            ->apply(Equipment::query()->with(['supplier', 'values']));

        return inertia('Equipment/List')->with([
            'equipments' => $equipments,
            'suppliers' => Supplier::all(),
        ]);
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
            'weight' => ['numeric'],
            'unit_value' => ['numeric'],
            'purchase_value' => ['numeric'],
            'replace_value' => ['numeric'],
            'min_qty' => ['integer'],
            'supplier_id' => ['nullable', 'exists:App\Models\Supplier,id'],
            'values.*.period_id' => ['required', 'exists:App\Models\Period,id'],
            'values.*.value' => ['required', 'numeric'],
        ]);

        $equipment = Equipment::create($request->input());

        if (isset($validated['values'])) {
            $equipment->values()->createMany($request->input('values'));
        }

        return redirect()
            ->route('equipments.index')
            ->with('flash', 'Equipamento cadastrado');
    }
}
