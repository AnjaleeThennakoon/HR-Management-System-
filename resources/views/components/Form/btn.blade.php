@props(['type' => 'submit', 'href' => null])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md bg-gray-800 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition']) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md bg-gray-800 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition']) }}>
        {{ $slot }}
    </button>
@endif
