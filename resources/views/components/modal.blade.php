@props(['id', 'title' => '', 'maxWidth' => 'md'])

@php
    $maxWidthClass = match ($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

<div id="{{ $id }}"
     class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm overflow-y-auto"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $id }}-title">
    <div class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 w-full {{ $maxWidthClass }} flex flex-col animate-[fadeIn_0.2s_ease-out] my-auto">

        <x-modal.header :id="$id" :title="$title" />

        {{-- Body --}}
        <div class="p-5 overflow-y-auto flex-1">
            {{ $slot }}
        </div>

        @isset($footer)
            <x-modal.footer>{{ $footer }}</x-modal.footer>
        @endisset
    </div>
</div>
