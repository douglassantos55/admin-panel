<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-suppliers');

        $suppliers = Supplier::query();

        if ($request->query()) {
            if ($request->query('name')) {
                $suppliers->where('social_name', 'like', '%' . $request->query('name') . '%');
            }
            if ($request->query('cnpj')) {
                $suppliers->where('cnpj', 'like', '%' . $request->query('cnpj') . '%');
            }
        }

        return inertia('Supplier/List')->with(
            'suppliers',
            $suppliers->paginate()->appends($request->query())
        );
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
