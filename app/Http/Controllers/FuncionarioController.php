<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ValidaComMensagens;
use App\Models\Clinica;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FuncionarioController extends Controller
{
    use ValidaComMensagens;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $busca = trim((string) $request->input('q'));

        $funcionarios = Funcionario::query()
            ->with(['user', 'clinica'])
            ->when($busca !== '', function ($query) use ($busca) {
                $digitos = preg_replace('/\D/', '', $busca);

                $query->where(function ($query) use ($busca, $digitos) {
                    $query->whereLike('nm_funcionario', "%{$busca}%")
                        ->orWhereLike('nm_email_funcionario', "%{$busca}%");

                    if ($digitos !== '') {
                        $query->orWhereLike('cd_cpf_funcionario', "%{$digitos}%");
                    }
                });
            })
            ->orderBy('nm_funcionario')
            ->get();

        return view('funcionarios.index', ['funcionarios' => $funcionarios, 'busca' => $busca]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('funcionarios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $dados = $this->dados($request);
        $clinica = Clinica::orderBy('id')->first();

        DB::transaction(function () use ($dados, $clinica) {
            $conta = User::create([
                'name' => $dados['nome'],
                'email' => $dados['email'],
                'password' => $dados['senha'],
                'perfil' => $dados['perfil'],
            ]);

            Funcionario::create([
                'nm_funcionario' => $dados['nome'],
                'nm_email_funcionario' => $dados['email'],
                'cd_cpf_funcionario' => $dados['cpf'],
                'cd_telefone_funcionario' => $dados['telefone'],
                'nm_especialidade_funcionario' => $dados['especialidade'],
                'cd_crmv_funcionario' => $dados['crmv'],
                'ic_ativo_funcionario' => true,
                'user_id' => $conta->id,
                'clinica_id' => $clinica?->id,
            ]);
        });

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Funcionario $funcionario): RedirectResponse
    {
        return redirect()->route('funcionarios.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Funcionario $funcionario): View
    {
        $funcionario->load('user');

        return view('funcionarios.edit', ['funcionario' => $funcionario]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Funcionario $funcionario): RedirectResponse
    {
        $dados = $this->dados($request, $funcionario);

        DB::transaction(function () use ($dados, $funcionario) {
            $conta = $funcionario->user;

            $atributosConta = [
                'name' => $dados['nome'],
                'email' => $dados['email'],
                'perfil' => $dados['perfil'],
            ];

            if (! empty($dados['senha'])) {
                $atributosConta['password'] = $dados['senha'];
            }

            if ($conta) {
                $conta->update($atributosConta);
            } else {
                $conta = User::create($atributosConta + ['password' => $dados['senha'] ?: Str::random(32)]);
            }

            $funcionario->update([
                'nm_funcionario' => $dados['nome'],
                'nm_email_funcionario' => $dados['email'],
                'cd_cpf_funcionario' => $dados['cpf'],
                'cd_telefone_funcionario' => $dados['telefone'],
                'nm_especialidade_funcionario' => $dados['especialidade'],
                'cd_crmv_funcionario' => $dados['crmv'],
                'user_id' => $conta->id,
            ]);
        });

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Funcionario $funcionario): RedirectResponse
    {
        if ($funcionario->user_id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir o próprio acesso.');
        }

        if ($funcionario->user) {
            $funcionario->user->delete();
        } else {
            $funcionario->delete();
        }

        return redirect()->route('funcionarios.index')->with('success', 'Funcionário excluído com sucesso.');
    }

    /**
     * Alterna o status ativo/inativo do funcionário.
     */
    public function status(Funcionario $funcionario): RedirectResponse
    {
        $funcionario->update(['ic_ativo_funcionario' => ! $funcionario->ic_ativo_funcionario]);

        return back()->with('success', $funcionario->ic_ativo_funcionario ? 'Funcionário reativado.' : 'Funcionário inativado.');
    }

    private function dados(Request $request, ?Funcionario $funcionario = null): array
    {
        $usuarioId = $funcionario?->user_id;
        $funcionarioId = $funcionario?->id_funcionario;

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'email' => [
                'required', 'email', 'max:120',
                Rule::unique('users', 'email')->ignore($usuarioId),
                Rule::unique('funcionario', 'nm_email_funcionario')->ignore($funcionarioId, 'id_funcionario'),
            ],
            'cpf' => [
                'required', 'string', 'max:14',
                Rule::unique('funcionario', 'cd_cpf_funcionario')->ignore($funcionarioId, 'id_funcionario'),
            ],
            'telefone' => ['required', 'string', 'max:20'],
            'especialidade' => ['nullable', 'string', 'max:80'],
            'crmv' => ['nullable', 'string', 'max:80'],
            'perfil' => ['required', Rule::in(array_keys(config('painel.perfis')))],
            'senha' => [$funcionario ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ], $this->mensagensValidacao(), $this->atributosValidacao());

        return [
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf' => preg_replace('/\D/', '', $dados['cpf']),
            'telefone' => preg_replace('/\D/', '', $dados['telefone']),
            'especialidade' => $dados['especialidade'] ?? null,
            'crmv' => $dados['crmv'] ?? null,
            'perfil' => $dados['perfil'],
            'senha' => $dados['senha'] ?? null,
        ];
    }
}
