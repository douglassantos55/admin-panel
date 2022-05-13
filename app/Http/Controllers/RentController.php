<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentRequest;
use App\Models\Customer;
use App\Models\Equipment;
use App\Models\Filters;
use App\Models\PaymentCondition;
use App\Models\PaymentMethod;
use App\Models\PaymentType;
use App\Models\Period;
use App\Models\Rent;
use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RentController extends Controller
{
    public function index(Filters $filters)
    {
        Gate::authorize('view-rents');

        $rents = $filters
            ->equals('number', 'id')
            ->equals('customer', 'customer_id')
            ->apply(Rent::with(['period', 'customer']));

        return inertia('Rent/List')->with([
            'rents' => $rents,
            'customers' => Customer::all(),
        ]);
    }

    public function view(Rent $rent)
    {
        Gate::authorize('view-rent');

        return inertia('Rent/View')->with(
            'rent',
            $rent->loadMissing([
                'period',
                'customer',
                'transporter',
                'paymentMethod',
                'paymentCondition',
            ])
        );
    }

    public function create()
    {
        Gate::authorize('create-rent');

        return inertia('Rent/Form')->with([
            'periods' => Period::all(),
            'customers' => Customer::all(),
            'equipments' => Equipment::with('values')->get(),
            'payment_types' => PaymentType::all(),
            'payment_methods' => PaymentMethod::all(),
            'payment_conditions' => PaymentCondition::all(),
            'transporters' => Transporter::all(),
        ]);
    }

    public function edit(Rent $rent)
    {
        Gate::authorize('update-rent');

        return inertia('Rent/Form')->with([
            'rent' => $rent,
            'periods' => Period::all(),
            'customers' => Customer::all(),
            'equipments' => Equipment::with('values')->get(),
            'payment_types' => PaymentType::all(),
            'payment_methods' => PaymentMethod::all(),
            'payment_conditions' => PaymentCondition::all(),
            'transporters' => Transporter::all(),
        ]);
    }

    public function store(RentRequest $request)
    {
        $rent = Rent::create($request->except('items'));
        $rent->items()->createMany($request->input('items'));

        return redirect()
            ->route('rents.view', $rent->id)
            ->with('flash', 'Locação cadastrada');
    }

    public function update(RentRequest $request, Rent $rent)
    {
        $rent->update($request->except('items'));

        $rent->items()->delete();
        $rent->items()->createMany($request->input('items'));

        return redirect()
            ->route('rents.view', $rent->id)
            ->with('flash', 'Dados atualizados');
    }
}
