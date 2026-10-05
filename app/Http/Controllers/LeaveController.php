<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\UseCases\Leave\DeleteLeaveInteractors;
use App\UseCases\Leave\ListLeaveInteractors;
use App\UseCases\Leave\Request\LeaveRequest;
use App\UseCases\Leave\StoreLeaveInteractors;
use App\UseCases\Leave\UpdateLeaveInteractors;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(ListLeaveInteractors $listLeaveInteractions): View
    {
        $leaves = $listLeaveInteractions->execute(
            request('search'),
            request('per_page'));

        return view('Leave.LeaveDashbord', [
            'leaves' => $leaves,
            'employees' => Employee::all(),
        ]);
    }

    public function store(LeaveRequest $leaveRequest, StoreLeaveInteractors $storeLeaveInteractors): RedirectResponse
    {
        $storeLeaveInteractors->execute($leaveRequest);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully created.');
    }

    public function update(string $id, LeaveRequest $request, UpdateLeaveInteractors $updateLeaveInteractors): RedirectResponse
    {
        $updateLeaveInteractors->execute($id, $request->validated());

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully updated.');
    }

    public function destroy(string $id, DeleteLeaveInteractors $deleteLeaveInteractors): RedirectResponse
    {
        $deleteLeaveInteractors->execute($id);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully deleted.');

    }
}
