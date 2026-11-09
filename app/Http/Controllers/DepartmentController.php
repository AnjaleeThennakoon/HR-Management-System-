<?php

namespace App\Http\Controllers;

use App\UseCases\Department\CreateDepartmentInteractor;
use App\UseCases\Department\DeleteDepartmentInteractor;
use App\UseCases\Department\ListDepartmentInteractor;
use App\UseCases\Department\Request\DepartmentRequest;
use App\UseCases\Department\UpdateDepartmentInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(ListDepartmentInteractor $listDepartmentInteractor): View
    {
        $departments = $listDepartmentInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Department.DepartmentDashBord', compact('departments'));
    }

    public function store(DepartmentRequest $departmentRequest, CreateDepartmentInteractor $createDepartmentInteractor): RedirectResponse
    {
        $createDepartmentInteractor->execute($departmentRequest->validated());

        return redirect('/departments');
    }

    public function update(DepartmentRequest $request, string $id, UpdateDepartmentInteractor $updateDepartmentInteractor): RedirectResponse
    {
        $updateDepartmentInteractor->execute($id, $request->validated());

        return redirect('/departments');
    }

    public function destroy(string $id, DeleteDepartmentInteractor $deleteDepartmentInteractor): RedirectResponse
    {
        $deleteDepartmentInteractor->execute($id);

        return redirect('/departments');
    }
}
