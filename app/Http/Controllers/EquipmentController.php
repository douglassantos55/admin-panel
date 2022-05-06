<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipmentRequest;
use App\Models\Equipment;
use App\Models\Filters;
use App\Models\Period;
use App\Models\Supplier;
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

    public function edit(Equipment $equipment)
    {
        Gate::authorize('update-equipment');

        return inertia('Equipment/Form')->with([
            'equipment' => $equipment->load(['supplier', 'values']),
            'periods' => Period::all(),
            'suppliers' => Supplier::all(),
        ]);
    }

    public function destroy(Equipment $equipment)
    {
        Gate::authorize('destroy-equipment');

        $equipment->delete();

        return redirect()
            ->route('equipments.index')
            ->with('flash', 'Equipamento excluido');
    }

    public function store(EquipmentRequest $request)
    {
        $equipment = Equipment::create($request->input());

        if ($request->input('values')) {
            $equipment->values()->createMany($request->input('values'));
        }

        return redirect()
            ->route('equipments.index')
            ->with('flash', 'Equipamento cadastrado');
    }

    public function update(EquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->input());

        $equipment->values()->delete();

        if ($request->input('values')) {
            $equipment->values()->createMany($request->input('values'));
        }

        return redirect()
            ->route('equipments.index')
            ->with('flash', 'Dados atualizados');
    }
}
