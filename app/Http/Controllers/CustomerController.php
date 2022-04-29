<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\CustomerRequest;

class CustomerController extends Controller
{
    public function index()
    {
        Gate::authorize('view-customers');

        return inertia('Customer/List')->with('customers', Customer::all());
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
        return redirect()->route('customers.index');
    }

    public function store(CustomerRequest $request)
    {
        Customer::create($request->input());
        return redirect()->route('customers.index');
    }

    public function update(Customer $customer, CustomerRequest $request)
    {
        $customer->update($request->input());
        return redirect()->route('customers.index');
    }
}
