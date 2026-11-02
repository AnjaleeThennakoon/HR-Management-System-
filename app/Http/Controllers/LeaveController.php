<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\SystemConfiguration;
use App\UseCases\Leave\DeleteLeaveInteractor;
use App\UseCases\Leave\LeaveAuthorizedInteractor;
use App\UseCases\Leave\LeaveBalanceInteractor;
use App\UseCases\Leave\ListLeaveInteractor;
use App\UseCases\Leave\Request\LeaveRequest;
use App\UseCases\Leave\StoreLeaveInteractor;
use App\UseCases\Leave\UpdateLeaveInteractor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeaveController extends Controller
{
    public function index(ListLeaveInteractor $listLeaveInteractions): View
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

    public function store(LeaveRequest $leaveRequest, StoreLeaveInteractor $storeLeaveInteractor): RedirectResponse|JsonResponse
    {
        $leave = $storeLeaveInteractor->execute($leaveRequest);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok', 'leave' => $leave]);
        }

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully created.');
    }

    public function update(string $id, LeaveRequest $leaveRequest, UpdateLeaveInteractor $updateLeaveInteractor): RedirectResponse
    {
        $leave = Leave::findOrFail($id);

        $updateLeaveInteractor->execute($leaveRequest, $leave);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully updated.');
    }

    public function LeaveAuthorizer(Request $request, LeaveAuthorizedInteractor $leaveAuthorizedInteractor): RedirectResponse
    {
        $validated = $request->validate([
            'leave_id' => ['required', 'integer', 'exists:leaves,id'],
            'status' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'remarks' => ['nullable', 'string'],
        ]);

        $leaveAuthorizedInteractor->execute($validated, (int) $request->user()->id);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave approval has been successfully updated.');
    }

    public function destroy(string $id, DeleteLeaveInteractor $deleteLeaveInteractor): RedirectResponse|JsonResponse
    {
        $deleteLeaveInteractor->execute($id);

        if (request()->wantsJson()) {
            return response()->json(['status' => 'ok']);
        }

        return redirect()->route('leaves.index')
            ->with('success', 'Leave has been successfully deleted.');
    }

    public function LeaveBalance(Request $request, LeaveBalanceInteractor $leaveBalanceInteractor): JsonResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'leave_type' => ['nullable', 'string', Rule::in(['Annual', 'Medical', 'casual'])],
            'exclude_leave_id' => ['nullable', 'integer', 'exists:leaves,id'],
        ]);

        $result = $leaveBalanceInteractor->execute($validated);

        return response()->json($result);
    }
}
