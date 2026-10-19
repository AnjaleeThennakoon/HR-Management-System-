<?php

namespace App\Http\Controllers;

use App\Models\LeaveApproval;
use App\UseCases\LeaveApproval\ListLeaveApprovalInteractors;
use App\UseCases\LeaveApproval\Request\LeaveApprovalRequest;
use App\UseCases\LeaveApproval\StoreLeaveApprovalInteractors;
use App\UseCases\LeaveApproval\UpdateLeaveApprovalInterctors;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class LeaveApprovalController extends Controller
{
    public function index(ListLeaveApprovalInteractors $listLeaveApprovalInteractors): View
    {
        $leaveApprovals = $listLeaveApprovalInteractors->execute(
            request('search'),
            request('per_page')
        );

        return view('LeaveApproval.LeaveApprovalDashboard', [
            'leaves' => $leaveApprovals,
        ]);
    }

    public function store(LeaveApprovalRequest $leaveApprovalRequest, StoreLeaveApprovalInteractors $storeLeaveApprovalInteractors): RedirectResponse
    {
        $storeLeaveApprovalInteractors->execute($leaveApprovalRequest);

        return redirect()->route('leave-approval.index')
            ->with('success', 'Leave approval has been successfully created.');
    }

    public function update(string $id, LeaveApprovalRequest $leaveApprovalRequest, UpdateLeaveApprovalInterctors $updateLeaveApprovalInteractors): RedirectResponse
    {
        $leaveApproval = LeaveApproval::findOrFail($id);

        $updateLeaveApprovalInteractors->execute($leaveApprovalRequest, $leaveApproval);

        return redirect()->route('leave-approval.index')
            ->with('success', 'LeaveApproval has been successfully updated.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $leaveApproval = LeaveApproval::findOrFail($id);
        $leaveApproval->delete();

        return redirect()->route('leave-approval.index')
            ->with('success', 'Leave approval has been successfully deleted.');
    }
}
