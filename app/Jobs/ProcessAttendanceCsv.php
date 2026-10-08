<?php

namespace App\Jobs;

use App\Models\AttendanceImport;
use App\UseCases\Attendance\UploadAttendanceInteractors;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessAttendanceCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $attendanceImportId;

    public int $userId;

    public function __construct(
        int $attendanceImportId,
        public string $filePath,
        int $userId
    ) {
        $this->attendanceImportId = $attendanceImportId;
        $this->userId = $userId;
    }

    /**
     * @throws \Exception
     */
    public function handle(UploadAttendanceInteractors $interactor): void
    {
        $attendanceImport = AttendanceImport::query()->findOrFail($this->attendanceImportId);
        $attendanceImport->update(['status' => 'processing']);

        $fullPath = Storage::disk('local')->path($this->filePath);

        if (! file_exists($fullPath)) {
            throw new \Exception("CSV file not found: {$fullPath}");
        }

        $result = $interactor->executeFromPath($fullPath);

        $attendanceImport->update([
            'status' => 'completed',
            'summary' => $result['summary'],
            'rows' => $result['rows'],
        ]);

        if (! empty($result['errors'])) {
            Log::warning('Attendance CSV import completed with invalid rows.', [
                'user_id' => $this->userId,
                'file_path' => $this->filePath,
                'errors' => $result['errors'],
            ]);
        }

        Storage::disk('local')->delete($this->filePath);
    }

    public function failed(?Throwable $exception): void
    {
        AttendanceImport::query()
            ->whereKey($this->attendanceImportId)
            ->update([
                'status' => 'failed',
                'error_message' => 'CSV processing failed. Please check the file and try again.',
            ]);
    }
}
