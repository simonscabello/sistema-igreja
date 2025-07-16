<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function index(): View
    {
        $this->authorize('visualizar_financeiro');

        return view('reports.financial.index');
    }

    public function monthly(Request $request): View|JsonResponse
    {
        $this->authorize('visualizar_financeiro');

        $month = $this->normalizeMonth($request->get('month', now()->month));
        $year = $request->get('year', now()->year);

        $transactions = FinancialTransaction::with('subcategory.financialCategory')
            ->whereYear('action_date', $year)
            ->whereMonth('action_date', $month)
            ->get();

        $entradas = $this->groupTransactionsByType($transactions, 'entrada');
        $saidas = $this->groupTransactionsByType($transactions, 'saida');

        $totalEntradas = $transactions->where('type', 'entrada')->sum('amount');
        $totalSaidas = $transactions->where('type', 'saida')->sum('amount');
        $saldoMensal = $totalEntradas - $totalSaidas;

        $report = [
            'entradas' => $entradas,
            'saidas' => $saidas,
            'total_entradas' => $totalEntradas,
            'total_saidas' => $totalSaidas,
            'saldo_mensal' => $saldoMensal,
            'periodo' => [
                'mes' => $month,
                'ano' => $year,
                'mes_nome' => Carbon::create($year, $month)->locale('pt_BR')->monthName
            ]
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($report);
        }

        $availableYears = $this->getAvailableYears();

        return view('reports.financial.monthly', compact('report', 'availableYears'));
    }

    public function annualDetailed(Request $request): View|JsonResponse
    {
        $this->authorize('visualizar_financeiro');

        $year = $request->get('year', now()->year);

        $transactions = FinancialTransaction::with('subcategory.financialCategory')
            ->whereYear('action_date', $year)
            ->orderBy('action_date', 'asc')
            ->get();

        $monthlyData = [];
        $yearlyTotals = ['entradas' => 0, 'saidas' => 0];

        for ($month = 1; $month <= 12; $month++) {
            $monthTransactions = $transactions->filter(function ($transaction) use ($month) {
                return $transaction->action_date->month == $month;
            });

            $entradas = $this->groupTransactionsByType($monthTransactions, 'entrada');
            $saidas = $this->groupTransactionsByType($monthTransactions, 'saida');

            $totalEntradas = $monthTransactions->where('type', 'entrada')->sum('amount');
            $totalSaidas = $monthTransactions->where('type', 'saida')->sum('amount');

            $yearlyTotals['entradas'] += $totalEntradas;
            $yearlyTotals['saidas'] += $totalSaidas;

            $monthlyData[$month] = [
                'mes_nome' => Carbon::create($year, $month)->locale('pt_BR')->monthName,
                'entradas' => $entradas,
                'saidas' => $saidas,
                'total_entradas' => $totalEntradas,
                'total_saidas' => $totalSaidas,
                'saldo_mensal' => $totalEntradas - $totalSaidas,
                'has_transactions' => count($entradas) > 0 || count($saidas) > 0
            ];
        }

        $report = [
            'monthly_data' => $monthlyData,
            'yearly_totals' => $yearlyTotals,
            'saldo_anual' => $yearlyTotals['entradas'] - $yearlyTotals['saidas'],
            'ano' => $year
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($report);
        }

        $availableYears = $this->getAvailableYears();

        return view('reports.financial.annual-detailed', compact('report', 'availableYears'));
    }

    public function annualSummary(Request $request): View|JsonResponse
    {
        $this->authorize('visualizar_financeiro');

        $year = $request->get('year', now()->year);

        $transactions = FinancialTransaction::with('subcategory.financialCategory')
            ->whereYear('action_date', $year)
            ->get();

        $monthlyData = [];
        $yearlyTotals = ['entradas' => 0, 'saidas' => 0];

        for ($month = 1; $month <= 12; $month++) {
            $monthTransactions = $transactions->filter(function ($transaction) use ($month) {
                return $transaction->action_date->month == $month;
            });

            $totalEntradas = $monthTransactions->where('type', 'entrada')->sum('amount');
            $totalSaidas = $monthTransactions->where('type', 'saida')->sum('amount');

            $yearlyTotals['entradas'] += $totalEntradas;
            $yearlyTotals['saidas'] += $totalSaidas;

            $monthlyData[$month] = [
                'mes_nome' => Carbon::create($year, $month)->locale('pt_BR')->monthName,
                'total_entradas' => $totalEntradas,
                'total_saidas' => $totalSaidas,
                'saldo_mensal' => $totalEntradas - $totalSaidas,
                'has_transactions' => $totalEntradas > 0 || $totalSaidas > 0
            ];
        }

        $report = [
            'monthly_data' => $monthlyData,
            'yearly_totals' => $yearlyTotals,
            'saldo_anual' => $yearlyTotals['entradas'] - $yearlyTotals['saidas'],
            'ano' => $year
        ];

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($report);
        }

        $availableYears = $this->getAvailableYears();

        return view('reports.financial.annual-summary', compact('report', 'availableYears'));
    }

    private function normalizeMonth(int|string $month): int
    {
        if (is_numeric($month)) {
            $month = (int) $month;
            return $month >= 1 && $month <= 12 ? $month : now()->month;
        }

        $monthNames = [
            'janeiro' => 1, 'fevereiro' => 2, 'março' => 3, 'abril' => 4,
            'maio' => 5, 'junho' => 6, 'julho' => 7, 'agosto' => 8,
            'setembro' => 9, 'outubro' => 10, 'novembro' => 11, 'dezembro' => 12
        ];

        $monthLower = mb_strtolower(trim($month));
        return $monthNames[$monthLower] ?? now()->month;
    }

    private function groupTransactionsByType($transactions, string $type): array
    {
        $filteredTransactions = $transactions->where('type', $type);

        $grouped = [];

        foreach ($filteredTransactions as $transaction) {
            $categoryName = $transaction->subcategory->financialCategory->name;
            $subcategoryName = $transaction->subcategory->name;
            $amount = $transaction->amount;

            if (!isset($grouped[$categoryName])) {
                $grouped[$categoryName] = [
                    'total_categoria' => 0,
                    'subcategorias' => []
                ];
            }

            if (!isset($grouped[$categoryName]['subcategorias'][$subcategoryName])) {
                $grouped[$categoryName]['subcategorias'][$subcategoryName] = 0;
            }

            $grouped[$categoryName]['subcategorias'][$subcategoryName] += $amount;
            $grouped[$categoryName]['total_categoria'] += $amount;
        }

        return $grouped;
    }

    private function getAvailableYears(): array
    {
        $years = FinancialTransaction::selectRaw('YEAR(action_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Se não houver transações, incluir pelo menos o ano atual
        if (empty($years)) {
            $years = [now()->year];
        }

        // Criar array associativo para o componente select
        return array_combine($years, $years);
    }

}
