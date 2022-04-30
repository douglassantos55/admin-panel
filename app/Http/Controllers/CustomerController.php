<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-customers');

        $customers = Customer::query();

        if ($request->query()) {
            if ($name = $request->query('name')) {
                $customers->where('name', 'like', '%' . $name . '%');
            }
            if ($cpf_cnpj = $request->query('cpf_cnpj')) {
                $customers->where('cpf_cnpj', 'like', '%' . $cpf_cnpj . '%');
            }
        }

        return inertia('Customer/List')->with(
            'customers',
            $customers->paginate()->appends($request->query())
        );
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
