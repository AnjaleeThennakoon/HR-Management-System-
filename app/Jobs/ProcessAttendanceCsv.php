<?php

namespace App\Jobs;

use App\UseCases\Attendance\UploadAttendanceInteractors;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAttendanceCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $filepath;

    public $userId;

    public function __construct(string $filepath,int $userId)
    {
        $this->filepath = $filepath;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @param  App\Services\AudioProcessor  $processor
     * @return void
     */
    public function handle(UploadAttendanceInteractors $interactors)
    {
        $file = new UploadedFile(
          storage_path('app/' . $this->filepath),
            'attendance.csv',
            'text/csv',
            null,
            true
        );
        $interactors->execute($file);
    }
}
