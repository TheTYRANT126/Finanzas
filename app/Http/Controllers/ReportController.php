<?php

namespace App\Http\Controllers;

use App\Services\FinancialSummary;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController
{
    public function __invoke(Request $request, FinancialSummary $summary): View
    {
        $month = $summary->month($request->query('month'));
        $user = $request->user();

        return view('reports.index', [
            'month' => $month,
            'totals' => $summary->totals($user, $month),
            'flow' => $summary->flow($user, $month),
        ]);
    }
}
