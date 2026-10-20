@props(['title', 'description'])

<div class="flex items-center justify-between mb-6 pb-5 border-b border-gray-100">
    <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50">
            {{ $icon }}
        </div>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">{{ $title }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $description }}</p>
        </div>
    </div>

    <a href="/dashboard"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 font-medium transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Back to Dashboard
    </a>
</div>
