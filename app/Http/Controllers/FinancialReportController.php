<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function __invoke(Request $request)
    {
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

        return view('reports.financial.monthly', compact('report'));
    }

    private function normalizeMonth($month)
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
} 