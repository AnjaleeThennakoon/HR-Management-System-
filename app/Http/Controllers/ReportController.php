<?php

namespace App\Http\Controllers;

use App\UseCases\Report\ListReportInteractor;
use App\UseCases\Report\Request\ReportRequest;
use App\UseCases\Report\ShowReportInteractor;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(ListReportInteractor $listReportInteractor)
    {
        return view('Report.ReportDashBord');
    }

    public function show(ReportRequest $reportRequest, ShowReportInteractor $showReportInteractor): View
    {
        return view('Report.ShowReport', $showReportInteractor->execute($reportRequest->date()));
    }
}
