<?php

namespace App\Http\Controllers;

use App\Rules\CpfCnpj;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    public function index()
    {
        return inertia('Customer/List')->with('customers', Customer::all());
    }

    public function create()
    {
        return inertia('Customer/Form');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index');
    }

    public function store(CpfCnpj $cpfRule, Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['nullable', 'email'],
            'birthdate' => ['nullable', 'date'],
            'cpf_cnpj' => ['required', $cpfRule, 'unique:App\Models\Customer'],
            'phone' => ['nullable', 'regex:/^\(\d{2}\) \d{4}-\d{4}$/'],
            'cellphone' => ['nullable', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'address.state' => ['nullable', 'size:2'],
            'address.postcode' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],
        ]);

        Customer::create([
            ...$request->except('address'),
            ...$request->post('address'),
        ]);

        return redirect()->route('customers.index');
    }
}
