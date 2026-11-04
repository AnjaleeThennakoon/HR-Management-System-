<?php

namespace App\UseCases\Attendance\Request;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function execute($date): array
    {
        $attendances = Attendance::with('employee')->whereDate('date', $date)->get();

        return $attendances->map(function ($attendance) {

            $inTime = $attendance->in_time;
            $outTime = $attendance->out_time;

            $workingHours = ($outTime && $inTime)
                ? Carbon::parse($inTime)->diffInHours(Carbon::parse($outTime))
                : 0;

            return [
                'employee_id' => $attendance->employee->id,
                'attendance_id' => $attendance->id,
                'first_name' => $attendance->employee->first_name,
                'last_name' => $attendance->employee->last_name,
                'in_time' => $inTime,
                'out_time' => $outTime,
                'work_hours' => $workingHours,
                'attendance_date' => $attendance->date,
                'sending_date' => $attendance->created_at,
            ];
        })->toArray();
    }
}
