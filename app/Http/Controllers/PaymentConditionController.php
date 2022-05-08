<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentConditionRequest;
use App\Models\PaymentCondition;
use App\Models\PaymentType;
use Illuminate\Support\Facades\Gate;

class PaymentConditionController extends Controller
{
    public function index()
    {
        Gate::authorize('view-payment-conditions');

        return inertia('PaymentCondition/List')
            ->with('payment_conditions', PaymentCondition::with('paymentType')->paginate());
    }

    public function create()
    {
        Gate::authorize('create-payment-condition');

        return inertia('PaymentCondition/Form')
            ->with('payment_types', PaymentType::all());
    }

    public function edit(PaymentCondition $paymentCondition)
    {
        Gate::authorize('update-payment-condition');

        return inertia('PaymentCondition/Form')
            ->with([
                'payment_condition' => $paymentCondition,
                'payment_types' => PaymentType::all(),
            ]);
    }

    public function store(PaymentConditionRequest $request)
    {
        Gate::authorize('create-payment-condition');

        PaymentCondition::create($request->input());

        return redirect()
            ->route('payment_conditions.index')
            ->with('flash', 'Condição cadastrada');
    }

    public function update(PaymentConditionRequest $request, PaymentCondition $paymentCondition)
    {
        Gate::authorize('update-payment-condition');

        $paymentCondition->update($request->input());

        return redirect()
            ->route('payment_conditions.index')
            ->with('flash', 'Dados atualizados');
    }
}
