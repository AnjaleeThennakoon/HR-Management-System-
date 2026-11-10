<?php

namespace App\Http\Controllers;

use App\UseCases\Report\LeveMonthlyReportInteractor;
use App\UseCases\Report\Request\DateReportRequest;
use App\UseCases\Report\Request\LateHoursReportRequest;
use App\UseCases\Report\Request\ReportRequest;
use App\UseCases\Report\ShowDailyReportInteractor;
use App\UseCases\Report\ShowLateHoursReportInteractor;
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
    public function show(ReportRequest $ReportRequest, ShowDailyReportInteractor $showDailyReportInteractor): View
    {
        $date = $ReportRequest->query('date') ?: $ReportRequest->route('date');
        $reportDate = $date ?: now()->toDateString();

        $attendances = $showDailyReportInteractor->execute($reportDate);

        return view('Report.Components.DailyReport', [
            'reportDate' => $reportDate,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Monthly attendance report
     * Route: monthlyReport/{month?}
     */
    public function monthlyReport(datereportRequest $dateReportRequest, ShowMonthlyReportInteractor $showMonthlyReportInteractor): View
    {
        $selectedMonth = $dateReportRequest->query('month') ?: $dateReportRequest->route('month');
        $selectedMonth = $selectedMonth ?: now()->format('Y-m');

        $monthlyReport = $showMonthlyReportInteractor->execute($selectedMonth);

        return view('Report.Components.monthlyReport', [
            'report' => $monthlyReport['employees'],
            'totals' => $monthlyReport['totals'],
            'month' => $selectedMonth,
        ]);
    }

    /**
     * Monthly LEAVE report
     * Route: leaveReport/{month?}
     */
    public function leaveMonthlyReport(datereportRequest $dateReportRequest, LeveMonthlyReportInteractor $LeveMonthlyReportInteractor): View
    {
        $selectedMonth = $dateReportRequest->query('month') ?: $dateReportRequest->route('month');
        $selectedMonth = $selectedMonth ?: now()->format('Y-m');

        $leaveReport = $LeveMonthlyReportInteractor->execute($selectedMonth);

        return view('Report.Components.monthlyLeaveReport', [
            'report' => $leaveReport,
            'month' => $selectedMonth,
        ]);
    }

    public function lateHoursReport(LateHoursReportRequest $request, ShowLateHoursReportInteractor $interactor): View {
        $data = $request->validated();

        $start = $data['start'] ?? now()->subDays(30)->toDateString();
        $end   = $data['end']   ?? now()->toDateString();

        $result = $interactor->execute(['start' => $start, 'end' => $end]);

        return view('Report.Components.lateHoursReport', [
            'report'    => $result['lateHours'],
            'dateRange' => $result['dateRange'],
        ]);
    }
}
