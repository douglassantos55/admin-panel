<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Rules\CpfCnpj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

    public function store(Request $request, CpfCnpj $cnpjRule)
    {
        Gate::authorize('create-supplier');

        $request->validate([
            'social_name' => ['required'],
            'email' => ['nullable', 'email'],
            'cnpj' => ['nullable', $cnpjRule, 'unique:App\Models\Supplier'],
            'phone' => ['nullable', 'regex:/^\(\d{2}\) \d{4}-\d{4}$/'],
            'address.state' => ['nullable', 'size:2'],
            'address.postcode' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],
        ]);

        Supplier::create([
            ...$request->except('address'),
            ...$request->input('address'),
        ]);

        return redirect()->route('suppliers.index');
    }
}
