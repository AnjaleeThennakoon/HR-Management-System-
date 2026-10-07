{{-- resources/views/Attendance/CsvUpload.blade.php --}}

{{-- CSV Upload Errors --}}
@php
    $hasCsvErrors = $errors->has('csv_file')
        || $errors->has('import_errors')
        || ($errors->any() && !$errors->has('employee_id') && !$errors->has('date') && !$errors->has('in_time') && !$errors->has('out_time'));
@endphp

@if($hasCsvErrors)
    <div class="mb-5 p-4 bg-red-50 text-red-700 text-sm rounded-lg ring-1 ring-red-100">
        <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <span class="font-semibold">CSV Import Errors</span>
        </div>
        <ul class="list-disc list-inside space-y-1 ml-7">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
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
