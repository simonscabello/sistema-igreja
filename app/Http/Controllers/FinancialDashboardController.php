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

        $period = $request->get('period', 30);
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($period);

        $transactions = FinancialTransaction::whereBetween('action_date', [$startDate, $endDate])
            ->selectRaw('DATE(action_date) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $entradas = [];
        $saidas = [];

        foreach ($transactions as $transaction) {
            $date = Carbon::parse($transaction->date)->format('Y-m-d');
            
            if ($transaction->type === 'entrada') {
                $entradas[$date] = $transaction->total;
            } else {
                $saidas[$date] = $transaction->total;
            }
        }

        $totalEntradas = $transactions->where('type', 'entrada')->sum('total');
        $totalSaidas = $transactions->where('type', 'saida')->sum('total');
        $saldo = $totalEntradas - $totalSaidas;

        return response()->json([
            'entradas' => $entradas,
            'saidas' => $saidas,
            'total_entradas' => $totalEntradas,
            'total_saidas' => $totalSaidas,
            'saldo' => $saldo,
            'periodo' => $period
        ]);
    }
} 