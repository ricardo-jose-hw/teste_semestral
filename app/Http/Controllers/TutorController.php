<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidaComMensagens;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TutorController extends Controller
{
    use ValidaComMensagens;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['nome', 'cpf', 'email', 'pet']);

        $tutores = Tutor::query()
            ->with('pets')
            ->when($filtros['nome'] ?? null, fn ($query, $valor) => $query->whereLike('nm_tutor', "%{$valor}%"))
            ->when($filtros['cpf'] ?? null, function ($query, $valor) {
                $cpf = preg_replace('/\D/', '', $valor);
                $query->whereLike('cd_cpf_tutor', "%{$cpf}%");
            })
            ->when($filtros['email'] ?? null, fn ($query, $valor) => $query->whereLike('nm_email_tutor', "%{$valor}%"))
            ->when($filtros['pet'] ?? null, fn ($query, $valor) => $query->whereHas(
                'pets',
                fn ($query) => $query->whereLike('nm_pet', "%{$valor}%")
            ))
            ->orderBy('nm_tutor')
            ->get();

        if ($request->ajax()) {
            return view('tutor._lista', ['tutores' => $tutores]);
        }

        return view('tutor.index', ['tutores' => $tutores, 'filtros' => $filtros]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tutor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Tutor::create($this->dados($request) + ['ic_ativo_tutor' => true]);

        return redirect()->route('tutores.index')->with('success', 'Cliente cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tutor $tutor): View
    {
        $tutor->load(['pets' => fn ($query) => $query->orderBy('nm_pet')]);

        return view('tutor.show', ['tutor' => $tutor]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tutor $tutor): View
    {
        return view('tutor.edit', ['tutor' => $tutor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tutor $tutor): RedirectResponse
    {
        $tutor->update($this->dados($request));

        return redirect()->route('tutores.show', $tutor)->with('success', 'Cliente atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tutor $tutor): RedirectResponse
    {
        $tutor->delete();

        return redirect()->route('tutores.index')->with('success', 'Cliente excluído com sucesso.');
    }

    private function dados(Request $request): array
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:120'],
            'endereco' => ['nullable', 'string', 'max:150'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'cpf' => ['nullable', 'string', 'max:14'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        return [
            'nm_tutor' => $dados['nome'],
            'nm_email_tutor' => $dados['email'] ?? null,
            'nm_endereco_tutor' => $dados['endereco'] ?? null,
            'cd_telefone_tutor' => $this->somenteDigitos($dados['telefone'] ?? null),
            'cd_cpf_tutor' => $this->somenteDigitos($dados['cpf'] ?? null),
        ];
    }

    private function somenteDigitos(?string $valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return preg_replace('/\D/', '', $valor);
    }
}
