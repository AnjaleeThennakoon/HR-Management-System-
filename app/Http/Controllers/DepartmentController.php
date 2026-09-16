<?php

namespace App\Http\Controllers;

use App\UseCases\Department\DeleteDepartmentInteractors;
use App\UseCases\Department\CreateDepartmentInteractor;
use App\UseCases\Department\ListDepartmentInteractors;
use App\UseCases\Department\Requests\DepartmentRequest;
use App\UseCases\Department\UpdateDepartmentInteractors;
use Illuminate\Http\RedirectResponse;



class DepartmentController extends Controller
{
    public function index(ListDepartmentInteractors $listDepartmentInteractor)
    {
        $departments = $listDepartmentInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Department.DepartmentDashBord', compact('departments'));    }

    public function store(DepartmentRequest $DepartmentRequest, CreateDepartmentInteractor $CreateDepartmentInteractor): RedirectResponse
    {
        $CreateDepartmentInteractor->execute($DepartmentRequest->toArray());

        return redirect('/departments');
    }

    public function update(DepartmentRequest $request, string $id, UpdateDepartmentInteractors $updateDepartmentInteractor): RedirectResponse
    {
        $updateDepartmentInteractor->execute($id, $request->toArray());

        return redirect('/departments');
    }

    public function destroy(string $id, DeleteDepartmentInteractors $deleteDepartmentInteractor): RedirectResponse
    {
        $deleteDepartmentInteractor->execute($id);

        return redirect('/departments');
    }
}
