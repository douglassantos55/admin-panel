<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentTypeController extends Controller
{
    public function create()
    {
        Gate::authorize('create-payment-type');

        return inertia('PaymentType/Form');
    }

    public function store(Request $request)
    {
        Gate::authorize('create-payment-type');

        $request->validate(['name' => 'required']);

        PaymentType::create($request->input());

        return redirect()
            ->route('payment_types.index')
            ->with('flash', 'Tipo cadastrado');
    }
}
