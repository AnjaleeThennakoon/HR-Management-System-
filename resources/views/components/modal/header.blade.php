@props(['id', 'title'])

<div class="flex items-center justify-between p-5 border-b border-gray-100">
    <h3 id="{{ $id }}-title" class="text-lg font-semibold text-gray-900">
        {{ $title }}
    </h3>
    <button type="button"
            onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
            class="text-gray-400 hover:text-gray-600 transition"
            aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
