<?php

namespace App\Http\Controllers;

use App\UseCases\Payroll\GeneratePayrollInteractor;
use App\UseCases\Payroll\ListPayrollInteractor;
use App\UseCases\Payroll\Request\PayrollRequest;
use App\UseCases\Payroll\StorePayrollInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PayrollController extends Controller
{
    public function index(ListPayrollInteractor $listPayrollInteractor): View
    {
        $payrolls = $listPayrollInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Payrolls.PayrollDashBord', compact('payrolls'));
    }

    public function store(PayrollRequest $payrollRequest, StorePayrollInteractor $storePayrollInteractor): RedirectResponse
    {
        $storePayrollInteractor->execute($payrollRequest);

        return redirect()->route('payrolls.index')
            ->with('success', 'Monthly payroll has been generated.');
    }

    public function generate(PayrollRequest $payrollRequest, GeneratePayrollInteractor $generatePayrollInteractor): View
    {
        $generatePayrollInteractor->execute($payrollRequest);

        return view('Payrolls.PayrollDashBord');
    }
}
