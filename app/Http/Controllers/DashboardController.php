<?php

namespace App\Http\Controllers;

use App\Models\Clinica;
use App\Models\Consulta;
use App\Models\Exame;
use App\Models\Pet;
use App\Models\Tutor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard.'.$request->user()->perfil);
    }

    public function dono(Request $request): View
    {
        return view('dashboards.dono', $this->dadosPainel($request));
    }

    public function funcionario(Request $request): View
    {
        return view('dashboards.funcionario', $this->dadosPainel($request));
    }

    private function dadosPainel(Request $request): array
    {
        $hoje = now()->toDateString();

        $agenda = Consulta::query()
            ->with(['pet.tutor', 'funcionario'])
            ->whereDate('dt_atendimento', $hoje)
            ->orderBy('hr_atendimento')
            ->get();

        $indicadores = [
            ['calendario', 'Consultas hoje', $agenda->count()],
            ['relogio', 'Aguardando confirmação', Consulta::where('ic_status_atendimento', 'Pendente')->count()],
            ['pet', 'Pets ativos', Pet::where('ic_ativo_pet', true)->count()],
            ['clientes', 'Clientes ativos', Tutor::where('ic_ativo_tutor', true)->count()],
        ];

        return [
            'indicadores' => $indicadores,
            'agenda' => $agenda,
            'hoje' => $hoje,
            'examesRecentes' => Exame::query()->with('consulta.pet')->latest('id_exame')->limit(5)->get(),
            'clinica' => Clinica::orderBy('id')->first(),
            'funcionario' => $request->user()->funcionario()->with('clinica')->first(),
        ];
    }
}
