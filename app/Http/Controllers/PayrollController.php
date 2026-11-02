<?php

namespace App\Http\Controllers;

use App\UseCases\Payroll\GeneratePayrollInteractors;
use App\UseCases\Payroll\ListPayrollInteractors;
use App\UseCases\Payroll\Request\PayrollRequest;
use App\UseCases\Payroll\StorePayrollInteractors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PayrollController extends Controller
{
    public function index(ListPayrollInteractors $listPayrollInteractor): View
    {
        $payrolls = $listPayrollInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Payrolls.PayrollDashBord', compact('payrolls'));
    }

    public function store(PayrollRequest $payrollRequest, StorePayrollInteractors $storePayrollInteractors, GeneratePayrollInteractors $generatePayrollInteractors): RedirectResponse
    {
        $storePayrollInteractors->execute($payrollRequest, $generatePayrollInteractors);

        return redirect()->route('payrolls.index')
            ->with('success', 'Monthly payroll has been generated.');
    }

    public function generate(PayrollRequest $payrollRequest, GeneratePayrollInteractors $generatePayrollInteractors): View
    {
        $generatePayrollInteractors->execute($payrollRequest);

        return view('Payrolls.PayrollDashBord');
    }
}
