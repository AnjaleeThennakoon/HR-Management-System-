<?php

namespace App\Http\Controllers;

use App\UseCases\Department\CreateDepartmentInteractor;
use App\UseCases\Department\DeleteDepartmentInteractors;
use App\UseCases\Department\ListDepartmentInteractors;
use App\UseCases\Department\Request\DepartmentRequest;
use App\UseCases\Department\UpdateDepartmentInteractors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    public function index(ListDepartmentInteractors $listDepartmentInteractor): View
    {
        $departments = $listDepartmentInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Department.DepartmentDashBord', compact('departments'));
    }

    public function store(
        DepartmentRequest $departmentRequest,
        CreateDepartmentInteractor $createDepartmentInteractor
    ): RedirectResponse {
        $createDepartmentInteractor->execute($departmentRequest->validated());

        return redirect('/departments');
    }

    public function update(
        DepartmentRequest $request,
        string $id,
        UpdateDepartmentInteractors $updateDepartmentInteractor
    ): RedirectResponse {
        $updateDepartmentInteractor->execute($id, $request->validated());

        return redirect('/departments');
    }

    public function destroy(string $id, DeleteDepartmentInteractors $deleteDepartmentInteractor): RedirectResponse
    {
        $deleteDepartmentInteractor->execute($id);

        return redirect('/departments');
    }
}
