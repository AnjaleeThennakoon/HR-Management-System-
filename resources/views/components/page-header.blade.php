@props(['title', 'description'])

<div class="flex items-center gap-3 mb-6 pb-5 border-b border-gray-200">
    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 flex-shrink-0">
        {{ $icon }}
    </div>
    <div>
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $title }}</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $description }}</p>
    </div>
</div>
