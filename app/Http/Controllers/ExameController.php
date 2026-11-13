<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidaComMensagens;
use App\Models\Consulta;
use App\Models\Exame;
use App\Models\Pet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ExameController extends Controller
{
    use ValidaComMensagens;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $petId = $request->input('pet_id');

        $exames = Exame::query()
            ->with(['consulta.pet', 'consulta.clinica'])
            ->when($petId, fn ($query, $valor) => $query->whereHas(
                'consulta',
                fn ($query) => $query->where('PET_id_pet', $valor)
            ))
            ->orderByDesc('id_exame')
            ->get();

        $pets = Pet::orderBy('nm_pet')->pluck('nm_pet', 'id_pet');

        return view('exame.index', ['exames' => $exames, 'pets' => $pets, 'petId' => $petId]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $consultaId = $request->integer('consulta_id') ?: null;

        $consulta = $consultaId
            ? Consulta::with(['pet.tutor', 'funcionario', 'exames'])->find($consultaId)
            : null;

        $concluidas = Consulta::query()
            ->with('pet.tutor')
            ->where('ic_status_atendimento', 'Concluída')
            ->orderByDesc('dt_atendimento')
            ->orderByDesc('hr_atendimento')
            ->get();

        return view('exame.create', ['consulta' => $consulta, 'concluidas' => $concluidas]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'consulta_id' => ['required', 'integer', 'exists:atendimento,id_atendimento'],
            'nome_exame' => ['required', 'array', 'min:1'],
            'nome_exame.*' => ['required', 'string', 'max:100'],
            'obs_exame' => ['nullable', 'array'],
            'obs_exame.*' => ['nullable', 'string', 'max:255'],
            'arquivo_exame' => ['required', 'array', 'min:1'],
            'arquivo_exame.*' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:5120'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        $consulta = Consulta::findOrFail($dados['consulta_id']);

        if ($consulta->ic_status_atendimento !== 'Concluída') {
            return back()->withInput()->with('error', 'Só consultas concluídas podem receber exames.');
        }

        $arquivos = $request->file('arquivo_exame', []);

        foreach ($dados['nome_exame'] as $indice => $nome) {
            $arquivo = $arquivos[$indice] ?? null;

            if (! $arquivo) {
                continue;
            }

            $nomeOriginal = $arquivo->getClientOriginalName();
            $extensao = $arquivo->getClientOriginalExtension();
            $nomeArquivo = Str::slug($nome).'-'.now()->format('YmdHis').'-'.Str::lower(Str::random(6)).'.'.$extensao;

            $arquivo->move(public_path('uploads/exames'), $nomeArquivo);

            Exame::create([
                'id_atendimento' => $consulta->id_atendimento,
                'nm_exame' => $nome,
                'nm_arquivo' => $nomeArquivo,
                'nm_arquivo_original' => $nomeOriginal,
                'ds_observacao' => $dados['obs_exame'][$indice] ?? null,
            ]);
        }

        return redirect()
            ->route('exames.index', ['pet_id' => $consulta->PET_id_pet])
            ->with('success', 'Exames anexados com sucesso.');
    }
}
