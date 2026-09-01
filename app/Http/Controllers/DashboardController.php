<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\FinancialTransaction;
use App\Models\Member;
use App\Models\Visitor;
use App\Models\WorshipSet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'aniversariantesDoMes' => $this->getAniversariantesDoMes(),
            'ultimosVisitantes' => $this->getUltimosVisitantes(),
            'visitantesQuerendoContato' => $this->getVisitantesQuerendoContato(),
            'proximoCulto' => $this->getProximoCulto(),
            'totalMembros' => $this->getTotalMembros(),
            'totalVisitantes' => $this->getTotalVisitantes(),
            'totalDepartamentos' => $this->getTotalDepartamentos(),
            'saldoAtual' => $this->getSaldoAtual(),
        ]);
    }

    private function getAniversariantesDoMes(): Collection
    {
        $mesAtual = Carbon::now()->month;
        $hoje = Carbon::now();

        return Member::with(['foto'])
            ->whereMonth('birth_date', $mesAtual)
            ->get()
            ->map(function ($membro) use ($hoje) {
                $isToday = $membro->birth_date
                    && $membro->birth_date->month === $hoje->month
                    && $membro->birth_date->day === $hoje->day;

                $foto = $membro->foto->first();
                $fotoUrl = $foto
                    ? asset('storage/'.$foto->path)
                    : asset('images/avatar-placeholder.png');

                return (object) [
                    'id' => $membro->id,
                    'nome' => $membro->full_name,
                    'mobile' => $membro->mobile,
                    'data' => $membro->birth_date ? $membro->birth_date->format('d/m') : '',
                    'tipo' => 'Membro',
                    'foto_url' => $fotoUrl,
                    'is_today' => $isToday,
                ];
            })
            ->sortBy([
                ['is_today', 'desc'],
                ['data', 'asc'],
            ])
            ->values();
    }

    private function getUltimosVisitantes(): Collection
    {
        return Visitor::select('id', 'name', 'mobile', 'visit_date', 'created_at', 'wants_contact')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($visitante) {
                $primeiraVisita = $visitante->visit_date
                    ? Carbon::parse($visitante->visit_date)
                    : $visitante->created_at;

                return (object) [
                    'id' => $visitante->id,
                    'nome' => $visitante->name,
                    'mobile' => $visitante->mobile,
                    'primeira_visita' => $primeiraVisita->format('d/m/Y'),
                    'wants_contact' => (bool) $visitante->wants_contact,
                ];
            });
    }

    private function getVisitantesQuerendoContato(): int
    {
        return Visitor::where('wants_contact', true)->count();
    }

    private function getProximoCulto(): ?array
    {
        $set = WorshipSet::withCount('songs')
            ->with(['assignments.worshipFunction', 'assignments.member'])
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('period')
            ->first();

        if (! $set) {
            return null;
        }

        $vocalNames = $set->assignments
            ->filter(fn ($assignment) => $assignment->worshipFunction?->slug === 'vocal')
            ->sortBy('order')
            ->map(fn ($assignment) => $assignment->member?->full_name)
            ->filter()
            ->values()
            ->all();

        $direcaoNames = $set->assignments
            ->filter(fn ($assignment) => $assignment->worshipFunction?->slug === 'direcao-do-culto')
            ->sortBy('order')
            ->map(fn ($assignment) => $assignment->member?->full_name)
            ->filter()
            ->values()
            ->all();

        return [
            'id' => $set->id,
            'date' => $set->formatted_date,
            'period_label' => $set->period_label,
            'songs_count' => $set->songs_count,
            'escala_definida' => $set->assignments->isNotEmpty(),
            'vocal' => $vocalNames !== [] ? implode(', ', $vocalNames) : null,
            'direcao' => $direcaoNames !== [] ? implode(', ', $direcaoNames) : null,
        ];
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

        return round((float) $receitas - (float) $despesas, 2);
    }
}
