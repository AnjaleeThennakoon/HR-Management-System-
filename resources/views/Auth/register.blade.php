<x-layout title="Register - HR System">
    <x-form title="Register an account" description="Start tracking today.">

        <form action="#" method="POST" class="mt-10 space-y-5">
            @csrf

            <x-form.field name="name" label="Name" placeholder="John Doe" />
            <x-form.field name="email" label="Email" type="email" placeholder="you@company.com" />
            <x-form.field name="password" label="Password" type="password" placeholder="••••••••" />
            <x-form.field name="password_confirmation" label="Confirm Password" type="password" placeholder="••••••••" />

            <button type="submit"
                    class="group relative flex w-full items-center justify-center gap-2 h-11 bg-indigo-600 text-white font-semibold rounded-lg shadow-sm transition-all duration-200 hover:bg-indigo-500 hover:shadow-md active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Create Account
            </button>

        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline font-medium">Log in</a>
        </p>

        <p class="text-center text-sm text-gray-500 mt-2">
            <a href="/" class="inline-flex items-center gap-1 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
        </p>

    </x-form>
</x-layout>
