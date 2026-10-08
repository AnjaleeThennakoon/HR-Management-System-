{{-- resources/views/Attendance/CsvUpload.blade.php --}}

@php
    $importSummary = $attendanceImport?->summary ?? session('import_summary');
    $importRows = $attendanceImport?->rows ?? session('import_rows', []);
    $hasImportFailures = $attendanceImport?->status === 'failed'
        || collect($importRows)->contains('status', 'fail');

    $hasCsvErrors = $hasImportFailures
        || $errors->has('csv_file')
        || ($errors->any() && !$errors->has('employee_id') && !$errors->has('date') && !$errors->has('in_time') && !$errors->has('out_time'));
@endphp

@if($attendanceImport)
    <script>
        const attendanceImportUrl = new URL(window.location.href);
        if (attendanceImportUrl.searchParams.get('import') !== '{{ $attendanceImport->id }};') {
            attendanceImportUrl.searchParams.set('import', '{{ $attendanceImport->id }}');
            window.history.replaceState({}, '', attendanceImportUrl);
        }
    </script>
@endif

@if($attendanceImport && in_array($attendanceImport->status, ['queued', 'processing'], true))
    <p id="attendanceImportStatus"
       class="mb-5 rounded-lg bg-blue-50 px-4 py-3 text-sm text-blue-800 ring-1 ring-blue-100"
       role="status"
       aria-live="polite"
       data-status-url="{{ route('attendance.imports.status', $attendanceImport->id) }}"
       data-import-id="{{ $attendanceImport->id }}"
       data-status="{{ $attendanceImport->status }}">
        {{ $attendanceImport->status === 'queued' ? 'CSV import is queued and waiting to start.' : 'CSV import is in progress.' }}
        This page will update automatically when it finishes.
    </p>
@endif

@if($hasCsvErrors)
    <div class="mb-5 rounded-xl ring-1 ring-red-200 bg-red-50/50 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-100 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-red-800">CSV Import Report</h3>
                    <p class="text-xs text-red-600 mt-0.5">
                        {{ $attendanceImport?->error_message ?? $importSummary ?? 'Import failed. Please check your CSV file.' }}
                    </p>
                    @foreach($errors->get('csv_file') as $uploadError)
                        <p class="text-xs text-red-700 mt-1">{{ $uploadError }}</p>
                    @endforeach
                </div>
            </div>

            @if(!empty($importRows))
                <button type="button" onclick="toggleErrorDetails()"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-red-700 text-xs font-medium rounded-lg ring-1 ring-red-200 hover:bg-red-50 transition whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                    <span id="errorDetailsToggleText">{{ $hasImportFailures ? 'Hide details' : 'See details' }}</span>
                </button>
            @endif
        </div>

        @if(!empty($importRows))
            <div id="errorDetailsList" class="{{ $hasImportFailures ? '' : 'hidden' }} border-t border-red-200 bg-white/50">
                <div class="max-h-96 overflow-y-auto p-4">
                    <table class="w-full text-xs">
                        <thead>
                        <tr class="text-left text-gray-500 border-b border-gray-200">
                            <th class="py-2 px-2 font-semibold">Row</th>
                            <th class="py-2 px-2 font-semibold">Employee ID</th>
                            <th class="py-2 px-2 font-semibold">Status</th>
                            <th class="py-2 px-2 font-semibold">Message</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($importRows as $row)
                            <tr class="border-b border-gray-100 {{ ($row['status'] ?? '') === 'success' ? 'bg-green-50/50' : 'bg-red-50/50' }}">
                                <td class="py-2 px-2 text-gray-600 font-medium">{{ $row['row_number'] ?? '-' }}</td>
                                <td class="py-2 px-2 text-gray-800">{{ $row['employee_id'] ?: 'N/A' }}</td>
                                <td class="py-2 px-2">
                                    @if(($row['status'] ?? '') === 'success')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-semibold">
                                            ✓ Success
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-semibold">
                                            ✗ Failed
                                        </span>
                                    @endif
                                </td>
                                <td class="py-2 px-2 text-gray-600">
                                    @if(($row['status'] ?? '') === 'fail')
                                        @foreach($row['errors'] ?? [] as $error)
                                            <p>{{ $error }}</p>
                                        @endforeach
                                    @else
                                        <span class="text-green-600">Imported successfully</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endif

@if($attendanceImport?->status === 'completed' && !$hasCsvErrors)
    <p class="mb-5 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 ring-1 ring-green-100"
       role="status">
        {{ $importSummary }}
    </p>
@endif

@if($attendanceImport && in_array($attendanceImport->status, ['queued', 'processing'], true))
    <script>
        const importStatus = document.getElementById('attendanceImportStatus');
        const pollImportStatus = async () => {
            try {
                const response = await fetch(importStatus.dataset.statusUrl, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(`Status check failed (${response.status}).`);
                }

                const result = await response.json();
                if (result.status === 'completed' || result.status === 'failed') {
                    window.location.reload();
                    return;
                }

                importStatus.textContent = result.status === 'processing'
                    ? 'CSV import is in progress. This page will update automatically when it finishes.'
                    : 'CSV import is queued and waiting to start. This page will update automatically when it finishes.';
                window.setTimeout(pollImportStatus, 2000);
            } catch (error) {
                importStatus.textContent = 'Unable to check import status. Retrying automatically.';
                window.setTimeout(pollImportStatus, 5000);
            }
        };

        window.setTimeout(pollImportStatus, 2000);
    </script>
@endif

{{-- CSV Upload Section --}}
<div class="mb-6 p-4 bg-blue-50 rounded-xl ring-1 ring-blue-100">
    <div class="flex items-center gap-2 mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
        </svg>
        <h3 class="text-sm font-semibold text-gray-800">Import Attendance from CSV</h3>
    </div>

    <form action="{{ route('attendance.upload') }}" method="POST" enctype="multipart/form-data"
          class="flex items-center gap-3">
        @csrf

        <label for="csv_file"
               class="flex-1 flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition
                      @error('csv_file') border-red-300 bg-red-50/30 @enderror">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <span id="csvFileName" class="text-sm text-gray-500">Choose CSV file...</span>
            <input type="file" name="csv_file" id="csv_file" accept=".csv" class="hidden" required
                   onchange="document.getElementById('csvFileName').textContent = this.files[0]?.name || 'Choose CSV file...'">
        </label>

        <button type="submit"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-blue-700 active:scale-[0.98] transition-all duration-200 whitespace-nowrap">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
            </svg>
            Upload CSV
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-2 ml-1">
        Format: <code class="bg-white px-1.5 py-0.5 rounded text-gray-700">employee_id,date,in_time,out_time</code>
    </p>
</div>
