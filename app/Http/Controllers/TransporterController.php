<?php

namespace App\Http\Controllers;

use App\Models\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransporterController extends Controller
{
    public function create()
    {
        Gate::authorize('create-transporter');

        return inertia('Transporter/Form');
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
}
