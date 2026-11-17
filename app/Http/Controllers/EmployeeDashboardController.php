<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\Support\LeaveSupport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403, 'Unauthorized access.');

        $employee = $user->employee ? $user->employee->load(['department', 'designation']) : null;

        $canViewProfile = $user->can('employees.view-own');
        $canViewDepartment = $user->can('departments.view-own');
        $canViewDesignation = $user->can('designations.view-own');
        $canViewAttendance = $user->can('attendance.view-own');
        $canStoreLeave = $user->can('leaves.store');
        $canUpdateLeave = $user->can('leaves.update');
        $canDestroyLeave = $user->can('leaves.destroy');
        $canViewHolidays = $user->can('holidays.view');
        $canViewPayroll = $user->can('payrolls.view-own');
        $canDownloadPayroll = $user->can('payrolls.download-own');

        // Attendance records & monthly stats
        $attendances = collect();
        $attendanceStats = [
            'present_days' => 0,
            'late_minutes' => 0,
            'ot_minutes' => 0,
        ];

        if ($canViewAttendance && $employee) {
            $attendances = Attendance::where('employee_id', $employee->id)
                ->orderBy('date', 'desc')
                ->take(30)
                ->get();

            $currentMonth = now()->month;
            $currentYear = now()->year;
            $monthAttendances = Attendance::where('employee_id', $employee->id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $currentMonth)
                ->get();

            $attendanceStats = [
                'present_days' => $monthAttendances->whereNotNull('in_time')->count(),
                'late_minutes' => (int) $monthAttendances->sum('late_minutes'),
                'ot_minutes' => (int) $monthAttendances->sum('ot_minutes'),
            ];
        }

        // Leaves & Balances
        $leaves = collect();
        $leaveBalances = [
            'Annual' => ['max_days' => 14, 'used_days' => 0, 'remaining_days' => 14],
            'Medical' => ['max_days' => 7, 'used_days' => 0, 'remaining_days' => 7],
            'casual' => ['max_days' => 7, 'used_days' => 0, 'remaining_days' => 7],
        ];

        if (($canStoreLeave || $canUpdateLeave || $canDestroyLeave) && $employee) {
            $leaves = Leave::where('employee_id', $employee->id)
                ->orderBy('start_date', 'desc')
                ->get();

            $leaveBalances = [
                'Annual' => LeaveSupport::getBalance($employee->id, 'Annual'),
                'Medical' => LeaveSupport::getBalance($employee->id, 'Medical'),
                'casual' => LeaveSupport::getBalance($employee->id, 'casual'),
            ];
        }

        // Company Holidays
        $holidays = collect();
        if ($canViewHolidays) {
            $holidays = Holiday::orderBy('date', 'asc')->get();
        }

        // Payroll records
        $payrolls = collect();
        if ($canViewPayroll && $employee) {
            $payrolls = Payroll::where('employee_id', $employee->id)
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();
        }

        return view('Employee.dashboard', [
            'user' => $user,
            'employee' => $employee,
            'canViewProfile' => $canViewProfile,
            'canViewDepartment' => $canViewDepartment,
            'canViewDesignation' => $canViewDesignation,
            'canViewAttendance' => $canViewAttendance,
            'canStoreLeave' => $canStoreLeave,
            'canUpdateLeave' => $canUpdateLeave,
            'canDestroyLeave' => $canDestroyLeave,
            'canViewHolidays' => $canViewHolidays,
            'canViewPayroll' => $canViewPayroll,
            'canDownloadPayroll' => $canDownloadPayroll,
            'attendances' => $attendances,
            'attendanceStats' => $attendanceStats,
            'leaves' => $leaves,
            'leaveBalances' => $leaveBalances,
            'holidays' => $holidays,
            'payrolls' => $payrolls,
        ]);
    }
}
