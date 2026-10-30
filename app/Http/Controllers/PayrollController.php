<?php

namespace App\Http\Controllers;

use App\UseCases\Payroll\GeneratePayrollInteractors;
use App\UseCases\Payroll\ListPayrollInteractors;
use App\UseCases\Payroll\Request\PayrollRequest;
use App\UseCases\Payroll\GenaratePayrollInteractors;
use App\UseCases\Payroll\StorePayrollInteractors;
use GenaratePayrollInteractors;
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

        return view('Payroll.PayrollDashBord', compact('payrolls'));
    }

    public function store(PayrollRequest $payrollRequest, StorePayrollInteractors $storePayrollInteractors): RedirectResponse
    {
        $storePayrollInteractors->execute($payrollRequest);

        return redirect()->route('payrolls.index')
            ->with('success', 'Monthly payroll has been generated.');
    }

    public function generate(PayrollRequest $payrollRequest, GeneratePayrollInteractors $generatePayrollInteractors): View
    {
        $generatePayrollInteractors->execute($payrollRequest);

        return view('Payroll.PayrollDashBord');
    }


}
