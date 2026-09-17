<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(){
        $employees = Employee::all();

        return view('Employee.employee',compact('employees'));
    }

    public function create(){

    }
}
