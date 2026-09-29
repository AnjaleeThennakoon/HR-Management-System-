@props(['name'])

@error($name)
<p class="text-sm text-red-500 mt-1" dusk="{{ $name }}-error">
    {{ $message }}
</p>
@enderror
