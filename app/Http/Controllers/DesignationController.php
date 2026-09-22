<?php

namespace App\Http\Controllers;

use App\UseCases\Designation\DeleteDesignationInteractors;
use App\UseCases\Designation\ListDesignationInteractors;
use App\UseCases\Designation\Request\DesignationRequest;
use App\UseCases\Designation\StoreDesignationInteractors;
use App\UseCases\Designation\UpdateDesignationInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DesignationController extends Controller
{
    public function index(
        ListDesignationInteractors $listDesignationInteractor): View
    {
        $designations = $listDesignationInteractor->execute(
            request('search'),
            request('per_page')
        );

        return view('Designation.Dashbord', ['designations' => $designations]);

    }

    public function store(DesignationRequest $designationRequest, StoreDesignationInteractors $storeDesignationInteractors
    ): RedirectResponse {
        $storeDesignationInteractors->execute($designationRequest->validated());

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(
        DesignationRequest $request,
        string $id,
        UpdateDesignationInteractor $updateDesignationInteractor
    ): RedirectResponse {
        $updateDesignationInteractor->execute(
            $id,
            $request->validated()
        );

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(
        string $id,
        DeleteDesignationInteractors $deleteDesignationInteractor
    ): RedirectResponse {
        $deleteDesignationInteractor->execute($id);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
