

<button onclick="openEditModal({{ $dept['id'] }}, {{ Js::from($dept['name']) }})"
        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-indigo-600 hover:bg-indigo-50 rounded-md text-xs font-semibold transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
    </svg>
    Edit
</button>
