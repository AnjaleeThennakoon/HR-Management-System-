@props(['name', 'label' => null, 'type' => 'text', 'value' => ''])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500']) }}
    >

    @error($name)
    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
