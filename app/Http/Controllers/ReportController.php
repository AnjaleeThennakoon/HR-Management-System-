<?php

namespace App\Http\Controllers;

use App\UseCases\Report\Request\ReportRequest;
use App\UseCases\Report\ShowReportInteractor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('Report.ReportDashBord');
    }

    public function show(ReportRequest $reportRequest, ShowReportInteractor $showReportInteractor): View
    {
        $date = $reportRequest->query('date') ?: $reportRequest->route('date');
        $reportDate = $date ? Carbon::parse($date)->toDateString() : null;

        return view('Report.Components.DailyReport', [
            'reportDate' => $reportDate,
            'attendances' => $reportDate ? $showReportInteractor->execute($reportDate) : [],
        ]);
    }

    public function monthlyReport(Request $request, ShowReportInteractor $interactor): View
    {
        $selectedMonth = $request->query('month') ?: $request->route('month');
        $report = [];
        $totals = null;

        if ($selectedMonth && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $selectedMonth) === 1) {
            $monthlyReport = $interactor->monthlyData($selectedMonth);
            $report = $monthlyReport['employees'];
            $totals = $monthlyReport['totals'];
        } else {
            $selectedMonth = null;
        }

        return view('Report.Components.monthlyReport', [
            'report' => $report,
            'totals' => $totals,
            'month' => $selectedMonth,
        ]);
    }
}
