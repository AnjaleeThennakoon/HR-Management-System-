<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Contracts\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::query()->get();

        return view('Employee.Employee', compact('employees'));
    }

    public function create() {}
}
