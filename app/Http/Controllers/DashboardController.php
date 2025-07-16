<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(): View
    {
        $dados = [
            'distribuicaoGenero' => $this->getDistribuicaoGeneroMembros(),
            'aniversariantesDoMes' => $this->getAniversariantesDoMes(),
            'ultimosVisitantes' => $this->getUltimosVisitantes(),
            'perfilEtario' => $this->getPerfilEtarioMembros(),
        ];

        return view('dashboard', $dados);
    }

    private function getDistribuicaoGeneroMembros(): EloquentCollection
    {
        return Member::selectRaw('
                CASE
                    WHEN gender = "Masculino" THEN "Masculino"
                    WHEN gender = "Feminino" THEN "Feminino"
                    ELSE "Não Informado"
                END as genero,
                COUNT(*) as total
            ')
            ->groupBy('genero')
            ->get();
    }

    private function getAniversariantesDoMes(): Collection
    {
        $mesAtual = Carbon::now()->month;

        // Aniversariantes membros
        $membroAniversariantes = Member::whereMonth('birth_date', $mesAtual)
            ->get()
            ->map(function ($membro) {
                return (object) [
                    'nome' => $membro->full_name,
                    'data' => $membro->birth_date ? $membro->birth_date->format('d/m') : '',
                    'tipo' => 'Membro'
                ];
            });

        // Aniversariantes visitantes (assumindo que alguns podem ter birth_date se adicionarmos depois)
        // Por enquanto vamos usar apenas membros já que visitantes não têm birth_date na estrutura atual

        return $membroAniversariantes->sortBy('data');
    }

    private function getUltimosVisitantes(): Collection
    {
        return Visitor::select('name', 'visit_date', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($visitante) {
                // Primeira visita é o created_at ou visit_date se informado
                $primeiraVisita = $visitante->visit_date
                    ? Carbon::parse($visitante->visit_date)
                    : $visitante->created_at;

                // Última visita é o updated_at se diferente do created_at, senão é a primeira
                $ultimaVisita = $visitante->updated_at->ne($visitante->created_at)
                    ? $visitante->updated_at
                    : $primeiraVisita;

                // Contar visitas baseado em quantos visitantes têm o mesmo nome/telefone
                $quantidadeVisitas = Visitor::where('name', $visitante->name)
                    ->when($visitante->mobile, function ($query, $mobile) {
                        return $query->orWhere('mobile', $mobile);
                    })
                    ->count();

                return (object) [
                    'nome' => $visitante->name,
                    'primeira_visita' => $primeiraVisita->format('d/m/Y'),
                    'ultima_visita' => $ultimaVisita->format('d/m/Y'),
                    'quantidade_visitas' => $quantidadeVisitas
                ];
            });
    }

    private function getPerfilEtarioMembros(): EloquentCollection
    {
        $hoje = Carbon::now();

        return Member::selectRaw('
                CASE
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18 THEN "<18"
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 18 AND 30 THEN "18–30"
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) BETWEEN 31 AND 50 THEN "31–50"
                    WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) > 50 THEN ">50"
                    ELSE "Não Informado"
                END as faixa_etaria,
                COUNT(*) as total
            ')
            ->whereNotNull('birth_date')
            ->groupBy('faixa_etaria')
            ->orderByRaw('
                CASE faixa_etaria
                    WHEN "<18" THEN 1
                    WHEN "18–30" THEN 2
                    WHEN "31–50" THEN 3
                    WHEN ">50" THEN 4
                    ELSE 5
                END
            ')
            ->get();
    }
}
