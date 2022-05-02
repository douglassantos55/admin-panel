<?php

namespace App\Http\Controllers;

use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeriodController extends Controller
{
    public function create()
    {
        Gate::authorize('create-period');

        return inertia('Period/Form');
    }

    public function store(Request $request)
    {
        Gate::authorize('create-period');

        $validated = $request->validate([
            'name' => ['required'],
            'qty_days' => ['required', 'integer'],
        ]);

        Period::create($validated);

        return redirect()
            ->route('periods.index')
            ->with('flash', 'Período cadastrado');
    }
}
