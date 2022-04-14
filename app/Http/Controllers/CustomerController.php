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
            'email' => ['email'],
            'birthdate' => ['date_format:d/m/Y'],
            'cpf_cnpj' => ['required', new CpfCnpj()],
            'phone' => ['regex:/^\(\d{2}\) \d{4}-\d{4}$/'],
            'cellphone' => ['regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'state' => ['size:2'],
            'postcode' => ['regex:/^\d{5}-\d{3}$/'],
        ]);

        Customer::create($validated);
        return redirect()->route('customers.index');
    }
}