<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class FinancialDashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('visualizar_financeiro');

        return view('financial-dashboard.index');
    }

    public function getData(Request $request): JsonResponse
    {
        $this->authorize('visualizar_financeiro');

        $period = $request->get('period', 'last_30_days');
        $dateRange = $this->getDateRangeByPeriod($period);

        $transactions = FinancialTransaction::whereBetween('action_date', $dateRange)->get();

        $monthlyData = $this->generateMonthlyData($transactions, $dateRange);

        return response()->json([
            'chart_data' => $monthlyData,
            'summary' => $this->generateSummary($transactions),
            'period_info' => $this->getPeriodInfo($period, $dateRange)
        ]);
    }

    private function getDateRangeByPeriod(string $period): array
    {
        return match ($period) {
            'yesterday' => [
                Carbon::yesterday()->startOfDay(),
                Carbon::yesterday()->endOfDay()
            ],
            'today' => [
                Carbon::today()->startOfDay(),
                Carbon::today()->endOfDay()
            ],
            'last_7_days' => [
                Carbon::now()->subDays(6)->startOfDay(),
                Carbon::now()->endOfDay()
            ],
            'last_30_days' => [
                Carbon::now()->subDays(29)->startOfDay(),
                Carbon::now()->endOfDay()
            ],
            'last_90_days' => [
                Carbon::now()->subDays(89)->startOfDay(),
                Carbon::now()->endOfDay()
            ],
            default => [
                Carbon::now()->subDays(29)->startOfDay(),
                Carbon::now()->endOfDay()
            ]
        };
    }

    private function generateMonthlyData($transactions, array $dateRange): array
    {
        $startDate = Carbon::parse($dateRange[0]);
        $endDate = Carbon::parse($dateRange[1]);
        
        $data = [
            'categories' => [],
            'entradas' => [],
            'saidas' => []
        ];

        // Determinar o agrupamento baseado no período
        $diffInDays = $endDate->diffInDays($startDate);
        
        if ($diffInDays <= 90) {
            // Para períodos até 90 dias, sempre agrupar por dia
            $current = $startDate->copy();
            $dailyData = [];
            
            while ($current <= $endDate) {
                $dateKey = $current->format('Y-m-d');
                $data['categories'][] = $current->format('d/m');
                $dailyData[$dateKey] = ['entrada' => 0, 'saida' => 0];
                $current->addDay();
            }
            
            foreach ($transactions as $transaction) {
                $dateKey = $transaction->action_date->format('Y-m-d');
                if (isset($dailyData[$dateKey])) {
                    $dailyData[$dateKey][$transaction->type] += $transaction->amount;
                }
            }
            
            foreach ($dailyData as $dayData) {
                $data['entradas'][] = $dayData['entrada'];
                $data['saidas'][] = $dayData['saida'];
            }
        } else {
            // Para períodos maiores que 90 dias, agrupar por mês
            $monthlyData = [];
            
            foreach ($transactions as $transaction) {
                $monthKey = $transaction->action_date->format('Y-m');
                if (!isset($monthlyData[$monthKey])) {
                    $monthlyData[$monthKey] = ['entrada' => 0, 'saida' => 0];
                }
                $monthlyData[$monthKey][$transaction->type] += $transaction->amount;
            }
            
            ksort($monthlyData);
            
            foreach ($monthlyData as $monthKey => $values) {
                $data['categories'][] = Carbon::createFromFormat('Y-m', $monthKey)->format('M/Y');
                $data['entradas'][] = $values['entrada'];
                $data['saidas'][] = $values['saida'];
            }
        }

        return $data;
    }

    private function generateSummary($transactions): array
    {
        $totalEntradas = $transactions->where('type', 'entrada')->sum('amount');
        $totalSaidas = $transactions->where('type', 'saida')->sum('amount');
        $saldo = $totalEntradas - $totalSaidas;

        return [
            'total_entradas' => $totalEntradas,
            'total_saidas' => $totalSaidas,
            'saldo' => $saldo,
            'total_transacoes' => $transactions->count()
        ];
    }

    private function getPeriodInfo(string $period, array $dateRange): array
    {
        $periodNames = [
            'yesterday' => 'Ontem',
            'today' => 'Hoje',
            'last_7_days' => 'Últimos 7 dias',
            'last_30_days' => 'Últimos 30 dias',
            'last_90_days' => 'Últimos 90 dias'
        ];

        return [
            'period' => $period,
            'display_name' => $periodNames[$period] ?? 'Período customizado',
            'start_date' => Carbon::parse($dateRange[0])->format('d/m/Y'),
            'end_date' => Carbon::parse($dateRange[1])->format('d/m/Y')
        ];
    }
} 