<?php

namespace App\Http\Controllers;

use App\Models\Filters;
use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function index(Filters $filters)
    {
        Gate::authorize('view-suppliers');

        $suppliers = $filters
            ->like('cnpj', 'cnpj')
            ->like('name', 'social_name')
            ->apply(Supplier::query());

        return inertia('Supplier/List')->with('suppliers', $suppliers);
    }

    public function create()
    {
        Gate::authorize('create-supplier');

        return inertia('Supplier/Form');
    }

    public function edit(Supplier $supplier)
    {
        Gate::authorize('update-supplier');

        return inertia('Supplier/Form')->with('supplier', $supplier);
    }

    public function destroy(Supplier $supplier)
    {
        Gate::authorize('destroy-supplier');

        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('flash', 'Fornecedor excluido');
    }

    public function store(SupplierRequest $request)
    {
        Supplier::create($request->input());

        return redirect()
            ->route('suppliers.index')
            ->with('flash', 'Fornecedor cadastrado');
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->input());

        return redirect()
            ->route('suppliers.index')
            ->with('flash', 'Dados atualizados');
    }
}
