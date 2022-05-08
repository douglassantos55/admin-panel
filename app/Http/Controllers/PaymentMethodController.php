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

    public function edit(PaymentMethod $paymentMethod)
    {
        Gate::authorize('update-payment-method');

        return inertia('PaymentMethod/Form')->with('payment_method', $paymentMethod);
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        Gate::authorize('destroy-payment-method');

        $paymentMethod->delete();

        return redirect()
            ->route('payment_methods.index')
            ->with('flash', 'Forma de pagamento excluida');
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

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        Gate::authorize('update-payment-method');

        $request->validate(['name' => 'required']);

        $paymentMethod->update($request->input());

        return redirect()
            ->route('payment_methods.index')
            ->with('flash', 'Dados atualizados');
    }
}
