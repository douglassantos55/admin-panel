<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentMethodController extends Controller
{
    public function index()
    {
        Gate::authorize('view-payment-methods');

        return inertia('PaymentMethod/List')
            ->with('payment_methods', PaymentMethod::paginate());
    }

    public function create()
    {
        Gate::authorize('create-payment-method');

        return inertia('PaymentMethod/Form');
    }

    public function store(Request $request)
    {
        Gate::authorize('create-payment-method');

        $request->validate(['name' => 'required']);

        PaymentMethod::create($request->input());

        return redirect()
            ->route('payment_methods.index')
            ->with('flash', 'Forma de pagamento cadastrada');
    }
}