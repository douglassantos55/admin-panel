<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Http\Requests\PeriodRequest;
use Illuminate\Support\Facades\Gate;

class PeriodController extends Controller
{
    public function index()
    {
        Gate::authorize('view-periods');

        return inertia('Period/List')->with('periods', Period::paginate());
    }

    public function create()
    {
        Gate::authorize('create-period');

        return inertia('Period/Form');
    }

    public function edit(Period $period)
    {
        Gate::authorize('update-period');

        return inertia('Period/Form')->with('period', $period);
    }

    public function destroy(Period $period)
    {
        Gate::authorize('destroy-period');

        $period->delete();

        return redirect()
            ->route('periods.index')
            ->with('flash', 'Periodo excluido');
    }

    public function store(PeriodRequest $request)
    {
        Period::create($request->input());

        return redirect()
            ->route('periods.index')
            ->with('flash', 'Período cadastrado');
    }

    public function update(PeriodRequest $request, Period $period)
    {
        $period->update($request->input());

        return redirect()
            ->route('periods.index')
            ->with('flash', 'Dados atualizados');
    }
}
