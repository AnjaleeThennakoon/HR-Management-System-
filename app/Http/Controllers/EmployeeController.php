<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\UseCases\Employee\Request\EmployeeRequest;
use App\UseCases\Employee\StoreEmployeeInteractors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::query()->get();

        return view('Employee.Employee', compact('employees'));
    }

    public function store(
        EmployeeRequest $employeeRequest,
        StoreEmployeeInteractors $storeEmployeeInteractors
    ): RedirectResponse {
        $storeEmployeeInteractors->execute($employeeRequest->validated());

        return redirect()->route('employees.index');
    }

    public function create() {}
}
