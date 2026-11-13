<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidaComMensagens;
use App\Models\Clinica;
use App\Models\Consulta;
use App\Models\Funcionario;
use App\Models\Pet;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConsultaController extends Controller
{
    use ValidaComMensagens;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['data', 'cliente', 'pet', 'status']);

        $consultas = Consulta::query()
            ->with(['pet.tutor', 'funcionario', 'clinica'])
            ->when($filtros['data'] ?? null, fn ($query, $valor) => $query->whereDate('dt_atendimento', $valor))
            ->when($filtros['cliente'] ?? null, fn ($query, $valor) => $query->whereHas(
                'pet.tutor',
                fn ($query) => $query->whereLike('nm_tutor', "%{$valor}%")
            ))
            ->when($filtros['pet'] ?? null, fn ($query, $valor) => $query->whereHas(
                'pet',
                fn ($query) => $query->whereLike('nm_pet', "%{$valor}%")
            ))
            ->when($filtros['status'] ?? null, fn ($query, $valor) => $query->where('ic_status_atendimento', $valor))
            ->orderByDesc('dt_atendimento')
            ->orderByDesc('hr_atendimento')
            ->get();

        return view('consulta.index', ['consultas' => $consultas, 'filtros' => $filtros]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $petSelecionado = $request->integer('pet_id') ?: null;
        $pet = $petSelecionado ? Pet::find($petSelecionado) : null;

        return view('consulta.create', [
            'consulta' => null,
            'pets' => $this->petsDisponiveis(),
            'funcionarios' => $this->funcionariosDisponiveis(),
            'clientes' => $this->clientesDisponiveis(),
            'petSelecionado' => $petSelecionado,
            'clienteSelecionado' => $pet?->id_tutor,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'pet_id' => ['required', 'integer', 'exists:pet,id_pet'],
            'funcionario_id' => ['required', 'integer', 'exists:funcionario,id_funcionario'],
            'data' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        $clinica = Clinica::orderBy('id')->first();

        Consulta::create([
            'PET_id_pet' => $dados['pet_id'],
            'FUNCIONARIO_id_funcionario' => $dados['funcionario_id'],
            'clinica_id' => $clinica?->id,
            'dt_atendimento' => $dados['data'],
            'hr_atendimento' => $dados['hora'],
            'ds_motivo' => $dados['motivo'] ?? null,
            'ic_status_atendimento' => 'Pendente',
        ]);

        return redirect()->route('consultas.index')->with('success', 'Consulta agendada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Consulta $consulta): View
    {
        $consulta->load(['pet.tutor', 'funcionario']);

        return view('consulta.edit', [
            'consulta' => $consulta,
            'pets' => $this->petsDisponiveis($consulta->PET_id_pet),
            'funcionarios' => $this->funcionariosDisponiveis($consulta->FUNCIONARIO_id_funcionario),
            'clientes' => $this->clientesDisponiveis($consulta->pet?->id_tutor),
            'petSelecionado' => $consulta->PET_id_pet,
            'clienteSelecionado' => $consulta->pet?->id_tutor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consulta $consulta): RedirectResponse
    {
        $dados = $request->validate([
            'funcionario_id' => ['required', 'integer', 'exists:funcionario,id_funcionario'],
            'data' => ['required', 'date', 'after_or_equal:today'],
            'hora' => ['required', 'date_format:H:i'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        $consulta->update([
            'FUNCIONARIO_id_funcionario' => $dados['funcionario_id'],
            'dt_atendimento' => $dados['data'],
            'hr_atendimento' => $dados['hora'],
            'ds_motivo' => $dados['motivo'] ?? null,
        ]);

        return redirect()->route('consultas.index')->with('success', 'Consulta reagendada com sucesso.');
    }

    /**
     * Atualiza o status da consulta.
     */
    public function status(Request $request, Consulta $consulta): RedirectResponse
    {
        $dados = $request->validate([
            'status' => ['required', Rule::in(array_keys(config('painel.classe_status')))],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        $consulta->update(['ic_status_atendimento' => $dados['status']]);

        return back()->with('success', 'Status da consulta atualizado.');
    }

    private function petsDisponiveis(?int $incluirId = null)
    {
        return Pet::query()
            ->with('tutor')
            ->where('ic_ativo_pet', true)
            ->when($incluirId, fn ($query) => $query->orWhere('id_pet', $incluirId))
            ->orderBy('nm_pet')
            ->get();
    }

    private function funcionariosDisponiveis(?int $incluirId = null)
    {
        return Funcionario::query()
            ->where('ic_ativo_funcionario', true)
            ->when($incluirId, fn ($query) => $query->orWhere('id_funcionario', $incluirId))
            ->orderBy('nm_funcionario')
            ->get();
    }

    private function clientesDisponiveis(?int $incluirId = null)
    {
        return Tutor::query()
            ->where('ic_ativo_tutor', true)
            ->when($incluirId, fn ($query) => $query->orWhere('id_tutor', $incluirId))
            ->orderBy('nm_tutor')
            ->pluck('nm_tutor', 'id_tutor');
    }
}
