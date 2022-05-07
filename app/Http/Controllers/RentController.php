<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Equipment;
use App\Models\PaymentMethod;
use App\Models\Period;
use App\Models\Rent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RentController extends Controller
{
    public function create()
    {
        Gate::authorize('create-rent');

        return inertia('Rent/Form')->with([
            'periods' => Period::all(),
            'customers' => Customer::all(),
            'equipments' => Equipment::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => ['required', 'exists:App\Models\Customer,id'],
            'period_id' => ['required', 'exists:App\Models\Period,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'payment_type_id' => ['required', 'exists:App\Models\PaymentType,id'],
            'payment_method_id' => ['required', 'exists:App\Models\PaymentMethod,id'],
            'payment_condition_id' => ['required', 'exists:App\Models\PaymentCondition,id'],
            'transporter_id' => ['required', 'exists:App\Models\Transporter,id'],
            'qty_days' => ['required', 'integer'],
            'discount' => ['nullable', 'numeric'],
            'paid_value' => ['nullable', 'numeric'],
            'bill' => ['nullable', 'required_unless:paid_value,null', 'numeric'],
            'check_info' => [
                'nullable',
                Rule::requiredIf(function () use ($request) {
                    $paymentMethod = PaymentMethod::find($request->input('payment_method_id'));
                    if (!$paymentMethod) {
                        return false;
                    }
                    return str($paymentMethod->name)->contains('Cheque');
                }),
            ],
            'items' => ['required', 'min:1'],
            'items.*.equipment_id' => ['required', 'exists:App\Models\Equipment,id'],
            'items.*.qty' => ['required', 'numeric'],
        ]);

        $rent = Rent::create($request->except('items'));
        $rent->items()->createMany($request->input('items'));
    }
}
