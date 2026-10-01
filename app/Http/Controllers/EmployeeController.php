<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\UseCases\Employee\DeleteEmployeeInteractors;
use App\UseCases\Employee\ListEmployeeInteractors;
use App\UseCases\Employee\Request\EmployeeRequest;
use App\UseCases\Employee\StoreEmployeeInteractors;
use App\UseCases\Employee\UpdateEmployeeInteractors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(ListEmployeeInteractors $listEmployeeInteractions): View
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

    public function store(EmployeeRequest $employeeRequest, StoreEmployeeInteractors $storeEmployeeInteractors): RedirectResponse
    {
        $storeEmployeeInteractors->execute($employeeRequest->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully created.');
    }

    public function update(EmployeeRequest $employeeRequest, UpdateEmployeeInteractors $updateEmployeeInteractors, string $id): RedirectResponse
    {

        $employee = Employee::findOrFail($id);

        $updateEmployeeInteractors->execute($employeeRequest, $employee);

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully updated.');
    }

    public function destroy(string $id, DeleteEmployeeInteractors $deleteEmployeeInteractors): RedirectResponse
    {
        $deleteEmployeeInteractors->execute($id);

        return redirect()->route('employees.index')
            ->with('success', 'Employee has been successfully deleted.');
    }
}
