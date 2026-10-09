<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Support\LeaveSupport;
use App\Models\SystemConfiguration;
use App\UseCases\Leave\DeleteLeaveInteractors;
use App\UseCases\Leave\ListLeaveInteractors;
use App\UseCases\Leave\Request\LeaveRequest;
use App\UseCases\Leave\StoreLeaveInteractors;
use App\UseCases\Leave\UpdateLeaveInteractors;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'leaveCounts' => [
                'Annual' => SystemConfiguration::getLeaveCount('Annual'),
                'Medical' => SystemConfiguration::getLeaveCount('Medical'),
                'casual' => SystemConfiguration::getLeaveCount('casual'),
            ],
        ]);
    }

    public function store(LeaveRequest $leaveRequest, StoreLeaveInteractors $storeLeaveInteractors): RedirectResponse
    {
        $storeLeaveInteractors->execute($leaveRequest);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully created.');
    }

    public function update(string $id, LeaveRequest $leaveRequest, UpdateLeaveInteractors $updateLeaveInteractors): RedirectResponse
    {
        $leave = Leave::findOrFail($id);

        $updateLeaveInteractors->execute($leaveRequest, $leave);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully updated.');
    }

    public function destroy(string $id, DeleteLeaveInteractors $deleteLeaveInteractors): RedirectResponse
    {
        $deleteLeaveInteractors->execute($id);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully deleted.');
    }

    public function getBalance(Request $leaveBalanceRequest): JsonResponse
    {
        $validated = $leaveBalanceRequest->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'leave_type' => ['required', 'string', Rule::in(['Annual', 'Medical', 'casual'])],
        ]);

        $balance = LeaveSupport::getBalance(
            (int) $validated['employee_id'],
            $validated['leave_type']
        );

        return response()->json([
            'success' => true,
            'balance' => $balance,
        ]);
    }
}
