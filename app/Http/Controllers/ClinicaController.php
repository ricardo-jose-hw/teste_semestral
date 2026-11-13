<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClinicaController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['nome' => ['required', 'string', 'max:255']], ['nome.required' => 'Informe o nome da clínica.']);
        $request->user()->clinicas()->create($data);

        return redirect()->route('dashboard.dono')->with('success', 'Clínica cadastrada com sucesso!');
    }
}