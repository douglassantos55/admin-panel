<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransporterController extends Controller
{
    public function index()
    {
        Gate::authorize('view-transporters');

        return inertia('Transporter/List')
            ->with('transporters', Transporter::paginate());
    }

    public function create()
    {
        Gate::authorize('create-transporter');

        return inertia('Transporter/Form');
    }

    public function edit(Transporter $transporter)
    {
        Gate::authorize('update-transporter');

        return inertia('Transporter/Form')->with('transporter', $transporter);
    }

    public function destroy(Transporter $transporter)
    {
        Gate::authorize('destroy-transporter');

        $transporter->delete();

        return redirect()
            ->route('transporters.index')
            ->with('flash', 'Transportador excluido');
    }

    public function store(Request $request)
    {
        Gate::authorize('create-transporter');

        $request->validate(['name' => 'required']);

        Transporter::create($request->input());

        return redirect()
            ->route('transporters.index')
            ->with('flash', 'Transportador cadastrado');
    }

    public function update(Request $request, Transporter $transporter)
    {
        Gate::authorize('update-transporter');

        $request->validate(['name' => 'required']);

        $transporter->update($request->input());

        return redirect()
            ->route('transporters.index')
            ->with('flash', 'Dados atualizados');
    }
}
