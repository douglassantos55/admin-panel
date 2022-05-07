<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PaymentTypeController extends Controller
{
    public function index()
    {
        Gate::authorize('view-payment-types');

        return inertia('PaymentType/List')
            ->with('payment_types', PaymentType::paginate());
    }

    public function create()
    {
        Gate::authorize('create-payment-type');

        return inertia('PaymentType/Form');
    }

    public function edit(PaymentType $paymentType)
    {
        Gate::authorize('update-payment-type');

        return inertia('PaymentType/Form')->with('payment_type', $paymentType);
    }

    public function destroy(PaymentType $paymentType)
    {
        Gate::authorize('destroy-payment-type');

        $paymentType->delete();

        return redirect()
            ->route('payment_types.index')
            ->with('flash', 'Tipo excluido');
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

    public function update(Request $request, PaymentType $paymentType)
    {
        Gate::authorize('update-payment-type');

        $request->validate(['name' => 'required']);

        $paymentType->update($request->input());

        return redirect()
            ->route('payment_types.index')
            ->with('flash', 'Dados atualizados');
    }
}
