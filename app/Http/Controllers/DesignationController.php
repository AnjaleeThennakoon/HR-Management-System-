<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\UseCases\Designation\DeleteDesignationInteractor;
use App\UseCases\Designation\ListDesignationInteractor;
use App\UseCases\Designation\Request\DesignationRequest;
use App\UseCases\Designation\StoreDesignationInteractor;
use App\UseCases\Designation\UpdateDesignationInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DesignationController extends Controller
{
    public function index(ListDesignationInteractor $listDesignationInteractor): View
    {
        $designations = $listDesignationInteractor->execute(
            request('search'),
            request('per_page'));

        $allDesignations = Designation::orderBy('level')->orderBy('name')->get();

        return view('Designation.DesignationDashbord', [
            'designations' => $designations,
            'allDesignations' => $allDesignations,
        ]);
    }

    public function store(DesignationRequest $designationRequest, StoreDesignationInteractor $storeDesignationInteractor): RedirectResponse
    {
        $storeDesignationInteractor->execute($designationRequest);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(DesignationRequest $designationRequest, string $id, UpdateDesignationInteractor $updateDesignationInteractor): RedirectResponse
    {
        $updateDesignationInteractor->execute($designationRequest);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(string $id, DeleteDesignationInteractor $deleteDesignationInteractor): RedirectResponse
    {
        $deleteDesignationInteractor->execute($id);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
