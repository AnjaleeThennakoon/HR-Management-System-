@props(['title' => null, 'description' => null])

<div class="bg-white rounded-lg shadow-lg p-10 max-w-md w-full">

    @if($title)
        <h1 class="text-2xl font-bold text-gray-800 text-center">
            {{ $title }}
        </h1>
    @endif

    @if($description)
        <p class="text-sm text-gray-500 text-center mt-2">
            {{ $description }}
        </p>
    @endif

    {{ $slot }}

</div>
