<?php

namespace App\Http\Controllers;

use App\UseCases\Report\LeveMonthlyReportInteractor;
use App\UseCases\Report\Request\ReportRequest;
use App\UseCases\Report\ShowDailyReportInteractor;
use App\UseCases\Report\ShowMonthlyReportInteractor;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('Report.ReportDashBord');
    }

    /**
     * Daily report
     * Route: dailyReport/{date?}
     */
    public function show(ReportRequest $reportRequest, ShowDailyReportInteractor $showDailyReportInteractor): View
    {
        $date = $reportRequest->query('date') ?: $reportRequest->route('date');
        $reportDate = $date ?: now()->toDateString();

        $attendances = $showDailyReportInteractor->execute($reportDate);

        return view('Report.Components.DailyReport', [
            'reportDate'  => $reportDate,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Monthly attendance report
     * Route: monthlyReport/{month?}
     */
    public function monthlyReport(ReportRequest $reportRequest, ShowMonthlyReportInteractor $showMonthlyReportInteractor): View
    {
        $selectedMonth = $reportRequest->query('month') ?: $reportRequest->route('month');
        $selectedMonth = $selectedMonth ?: now()->format('Y-m');

        $monthlyReport = $showMonthlyReportInteractor->execute($selectedMonth);

        return view('Report.Components.monthlyReport', [
            'report' => $monthlyReport['employees'],
            'totals' => $monthlyReport['totals'],
            'month'  => $selectedMonth,
        ]);
    }

    /**
     * Monthly LEAVE report
     * Route: leaveReport/{month?}
     */
    public function leaveReport(ReportRequest $reportRequest, LeveMonthlyReportInteractor $LeveMonthlyReportInteractor): View
    {
        $selectedMonth = $reportRequest->query('month') ?: $reportRequest->route('month');
        $selectedMonth = $selectedMonth ?: now()->format('Y-m');

        $leaveReport = $LeveMonthlyReportInteractor->execute($selectedMonth);

        return view('Report.Components.LeaveReport', [
            'report' => $leaveReport,
            'month'  => $selectedMonth,
        ]);
    }
}
