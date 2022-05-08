<?php

namespace App\Http\Controllers;

use App\Models\PaymentCondition;
use App\Models\PaymentType;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        Gate::authorize('create-payment-condition');

        $request->validate([
            'name' => ['required'],
            'title' => ['required'],
            'increment' => ['nullable', 'numeric', 'min:0'],
            'payment_type_id' => ['required', 'exists:App\Models\PaymentType,id'],
            'installments.*' => ['nullable', 'integer', 'numeric', 'gte:0'],
        ]);

        PaymentCondition::create($request->input());

        return redirect()
            ->route('payment_conditions.index')
            ->with('flash', 'Condição cadastrada');
    }
}
