<?php

namespace App\Http\Controllers;

use App\Models\Filters;
use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function index(Filters $filters)
    {
        Gate::authorize('view-customers');

        $customers = $filters
            ->like('name', 'name')
            ->like('cpf_cnpj', 'cpf_cnpj')
            ->apply(Customer::query());

        return inertia('Customer/List')->with('customers', $customers);
    }

    public function create()
    {
        Gate::authorize('create-customer');

        return inertia('Customer/Form');
    }

    public function edit(Customer $customer)
    {
        Gate::authorize('update-customer');

        return inertia('Customer/Form')->with('customer', $customer);
    }

    public function destroy(Customer $customer)
    {
        Gate::authorize('destroy-customer');

        $customer->delete();
        return redirect()->route('customers.index')->with('flash', 'Cliente excluido');
    }

    public function store(CustomerRequest $request)
    {
        Customer::create($request->input());
        return redirect()->route('customers.index')->with('flash', 'Cliente cadastrado');
    }

    public function update(Customer $customer, CustomerRequest $request)
    {
        $customer->update($request->input());
        return redirect()->route('customers.index')->with('flash', 'Dados atualizados');
    }
}
