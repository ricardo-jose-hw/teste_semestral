<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidaComMensagens;
use App\Models\Pet;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PetController extends Controller
{
    use ValidaComMensagens;

    /**
     * Lista global de pets, filtrável por cliente.
     */
    public function index(Request $request): View
    {
        $filtros = $request->only(['cliente_id', 'q']);

        $pets = Pet::query()
            ->with('tutor')
            ->when($filtros['cliente_id'] ?? null, fn ($query, $valor) => $query->where('id_tutor', $valor))
            ->when($filtros['q'] ?? null, function ($query, $valor) {
                $query->where(function ($query) use ($valor) {
                    $query->whereLike('nm_pet', "%{$valor}%")
                        ->orWhereLike('nm_especie_pet', "%{$valor}%")
                        ->orWhereLike('nm_raca_pet', "%{$valor}%");
                });
            })
            ->orderBy('nm_pet')
            ->get();

        $clientes = Tutor::orderBy('nm_tutor')->pluck('nm_tutor', 'id_tutor');

        return view('pet.index', [
            'pets' => $pets,
            'clientes' => $clientes,
            'filtros' => $filtros,
            'tutor' => null,
        ]);
    }

    /**
     * Lista de pets de um cliente.
     */
    public function doTutor(Tutor $tutor): View
    {
        $pets = $tutor->pets()->orderBy('nm_pet')->get();
        $clientes = Tutor::orderBy('nm_tutor')->pluck('nm_tutor', 'id_tutor');

        return view('pet.index', [
            'pets' => $pets,
            'clientes' => $clientes,
            'filtros' => ['cliente_id' => (string) $tutor->id_tutor, 'q' => ''],
            'tutor' => $tutor,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('pet.create', [
            'clientes' => $this->clientesAtivos(),
            'tutorSelecionado' => $request->integer('tutor_id') ?: null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Pet::create($this->dados($request) + ['ic_ativo_pet' => true]);

        return redirect()->route('pets.index')->with('success', 'Pet cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): View
    {
        $pet->load([
            'tutor',
            'consultas' => fn ($query) => $query
                ->with(['funcionario', 'exames'])
                ->orderByDesc('dt_atendimento')
                ->orderByDesc('hr_atendimento'),
        ]);

        return view('pet.show', ['pet' => $pet]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pet $pet): View
    {
        $clientes = Tutor::query()
            ->where('ic_ativo_tutor', true)
            ->orWhere('id_tutor', $pet->id_tutor)
            ->orderBy('nm_tutor')
            ->pluck('nm_tutor', 'id_tutor');

        return view('pet.edit', ['pet' => $pet, 'clientes' => $clientes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pet $pet): RedirectResponse
    {
        $pet->update($this->dados($request));

        return redirect()->route('pets.show', $pet)->with('success', 'Pet atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();

        return redirect()->route('pets.index')->with('success', 'Pet excluído com sucesso.');
    }

    /**
     * Alterna o status ativo/inativo do pet.
     */
    public function status(Pet $pet): RedirectResponse
    {
        $pet->update(['ic_ativo_pet' => ! $pet->ic_ativo_pet]);

        return back()->with('success', $pet->ic_ativo_pet ? 'Pet reativado.' : 'Pet inativado.');
    }

    private function clientesAtivos()
    {
        return Tutor::where('ic_ativo_tutor', true)->orderBy('nm_tutor')->pluck('nm_tutor', 'id_tutor');
    }

    private function dados(Request $request): array
    {
        $dados = $request->validate([
            'id_tutor' => ['required', 'integer', 'exists:tutor,id_tutor'],
            'nome' => ['required', 'string', 'max:50'],
            'especie' => ['required', 'string', 'max:50'],
            'raca' => ['nullable', 'string', 'max:50'],
            'peso' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        return [
            'id_tutor' => $dados['id_tutor'],
            'nm_pet' => $dados['nome'],
            'nm_especie_pet' => $dados['especie'],
            'nm_raca_pet' => $dados['raca'] ?? null,
            'qt_peso_pet' => $dados['peso'] ?? null,
        ];
    }
}
