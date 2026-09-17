<?php

namespace App\Http\Controllers;


use App\UseCases\Designation\Request\DesignationRequest;
use CreateDesignationInteractor;
use DeleteDesignationInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\UseCases\Designation\DeleteDesignationInteractor;
use App\UseCases\Designation\ListDesignationInteractors;
use UpdateDesignationInteractor;


class DesignationController extends Controller
{
    public function index(
        ListDesignationInteractors $listDesignationInteractor
    ): View {
        $designations = $listDesignationInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view(
            'Designation.DesignationDashBord',
            compact('designations')
        );
    }

    public function store(
        DesignationRequest $request,
        CreateDesignationInteractor $createDesignationInteractor
    ): RedirectResponse {
        $createDesignationInteractor->execute($request);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(
        DesignationRequest $request,
        string $id,
        UpdateDesignationInteractor $updateDesignationInteractor
    ): RedirectResponse {
        $request->id = $id;

        $updateDesignationInteractor->execute(
            $request,
            $id
        );

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(
        string $id,
        DeleteDesignationInteractor $deleteDesignationInteractor
    ): RedirectResponse {
        $deleteDesignationInteractor->execute($id);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
