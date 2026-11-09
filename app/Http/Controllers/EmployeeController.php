<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\UseCases\Employee\DeleteEmployeeInteractor;
use App\UseCases\Employee\ListEmployeeInteractor;
use App\UseCases\Employee\Request\EmployeeRequest;
use App\UseCases\Employee\StoreEmployeeInteractor;
use App\UseCases\Employee\UpdateEmployeeInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(ListEmployeeInteractor $listEmployeeInteractions): View
    {
        $employees = $listEmployeeInteractions->execute(
            request('search'),
            request('per_page'));

        return view('Employee.EmployeeDashbord', [
            'employees' => $employees,
            'departments' => Department::all(),
            'designations' => Designation::all(),
        ]);
    }

    public function store(EmployeeRequest $employeeRequest, StoreEmployeeInteractor $storeEmployeeInteractor): RedirectResponse
    {
        $storeEmployeeInteractor->execute($employeeRequest->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully created.');
    }

    public function update(string $id, EmployeeRequest $employeeRequest, UpdateEmployeeInteractor $updateEmployeeInteractor): RedirectResponse
    {
        $employee = Employee::findOrFail($id);

        $updateEmployeeInteractor->execute($employeeRequest, $employee);

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully updated.');
    }

    public function destroy(string $id, DeleteEmployeeInteractor $deleteEmployeeInteractor): RedirectResponse
    {
        $deleteEmployeeInteractor->execute($id);

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully deleted.');
    }
}
