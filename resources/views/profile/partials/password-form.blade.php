<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
    <x-alert.success />

    @if($errors->any())
        <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-semibold">Unable to update your password.</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6">
        <h2 class="text-base font-semibold text-gray-900">Change Password</h2>
        <p class="mt-1 text-sm text-gray-500">Enter your current password, then choose a new password.</p>
        <p class="mt-3 text-sm text-gray-700">
            Account: <span class="font-medium">{{ $user->name }}</span>
            <span class="text-gray-400">({{ $user->email }})</span>
        </p>
    </div>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <input type="hidden" name="name" value="{{ $user->name }}">
        <input type="hidden" name="email" value="{{ $user->email }}">

        <div>
            <label for="current_password" class="mb-1.5 block text-sm font-medium text-gray-700">
                Current Password
            </label>
            <input id="current_password" name="current_password" type="password"
                   autocomplete="current-password" required
                   class="w-full rounded-lg border @error('current_password') border-red-500 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="Enter your current password">
            @error('current_password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700">
                New Password
            </label>
            <input id="password" name="password" type="password" minlength="8"
                   autocomplete="new-password" required
                   class="w-full rounded-lg border @error('password') border-red-500 @else border-gray-300 @enderror px-3.5 py-2.5 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                   placeholder="At least 8 characters">
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Use at least 8 characters.</p>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Update Password
            </button>
        </div>
    </form>
</div>
