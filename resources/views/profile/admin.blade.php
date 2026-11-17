<x-app-layout title="Admin Profile - HR System">
    <div class="p-6 max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Administrator Profile</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your admin account and password.</p>
        </div>

        @include('profile.partials.password-form', ['user' => $user])
    </div>
</x-app-layout>
