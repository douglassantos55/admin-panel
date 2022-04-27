<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Rules\CpfCnpj;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        return inertia('Customer/Form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['nullable', 'email'],
            'birthdate' => ['nullable', 'date'],
            'cpf_cnpj' => ['required', new CpfCnpj(), 'unique:App\Models\Customer'],
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
