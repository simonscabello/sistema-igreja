<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use App\Models\Department;
use App\Models\FinancialTransaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(): View
    {
        $dados = [
            'aniversariantesDoMes' => $this->getAniversariantesDoMes(),
            'ultimosVisitantes' => $this->getUltimosVisitantes(),
            'totalMembros' => $this->getTotalMembros(),
            'totalVisitantes' => $this->getTotalVisitantes(),
            'totalDepartamentos' => $this->getTotalDepartamentos(),
            'saldoAtual' => $this->getSaldoAtual(),
        ];

        return view('dashboard', $dados);
    }

    private function getAniversariantesDoMes(): Collection
    {
        $mesAtual = Carbon::now()->month;
        $hoje = Carbon::now();

        // Aniversariantes membros
        $membroAniversariantes = Member::with(['foto'])
            ->whereMonth('birth_date', $mesAtual)
            ->get()
            ->map(function ($membro) use ($hoje) {
                $isToday = $membro->birth_date && 
                          $membro->birth_date->month === $hoje->month && 
                          $membro->birth_date->day === $hoje->day;

                $foto = $membro->foto->first();
                $fotoUrl = $foto ? asset('storage/' . $foto->path) : asset('images/avatar-placeholder.png');

                return (object) [
                    'nome' => $membro->full_name,
                    'mobile' => $membro->mobile,
                    'data' => $membro->birth_date ? $membro->birth_date->format('d/m') : '',
                    'tipo' => 'Membro',
                    'foto_url' => $fotoUrl,
                    'is_today' => $isToday
                ];
            });

        // Aniversariantes visitantes (assumindo que alguns podem ter birth_date se adicionarmos depois)
        // Por enquanto vamos usar apenas membros já que visitantes não têm birth_date na estrutura atual

        return $membroAniversariantes->sortBy([
            ['is_today', 'desc'], // Aniversários de hoje primeiro
            ['data', 'asc']       // Depois por ordem de data
        ]);
    }

    private function getUltimosVisitantes(): Collection
    {
        return Visitor::select('id', 'name', 'mobile', 'visit_date', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($visitante) {
                // Primeira visita é o created_at ou visit_date se informado
                $primeiraVisita = $visitante->visit_date
                    ? Carbon::parse($visitante->visit_date)
                    : $visitante->created_at;
             
                return (object) [
                    'id' => $visitante->id,
                    'nome' => $visitante->name,
                    'mobile' => $visitante->mobile,
                    'primeira_visita' => $primeiraVisita->format('d/m/Y'),
                ];
            });
    }

    private function getTotalMembros(): int
    {
        return Member::count();
    }

    private function getTotalVisitantes(): int
    {
        return Visitor::count();
    }

    private function getTotalDepartamentos(): int
    {
        return Department::count();
    }

    private function getSaldoAtual(): float
    {
        $receitas = FinancialTransaction::where('type', 'entrada')->sum('amount');
        $despesas = FinancialTransaction::where('type', 'saida')->sum('amount');
        
        return $receitas - $despesas;
    }

}
