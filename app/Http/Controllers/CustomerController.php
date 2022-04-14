<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Rules\CpfCnpj;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function create()
    {
        return inertia('Customer/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['nullable', 'email'],
            'birthdate' => ['nullable', 'date'],
            'cpf_cnpj' => ['required', new CpfCnpj()],
            'phone' => ['nullable', 'regex:/^\(\d{2}\) \d{4}-\d{4}$/'],
            'cellphone' => ['nullable', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'state' => ['nullable', 'size:2'],
            'postcode' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],
        ]);

        Customer::create($validated);
        return redirect()->route('customers.index');
    }
}
