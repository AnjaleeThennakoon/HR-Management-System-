<x-layout title="Login - HR System">
    <x-form title="Log in to your account" description="Welcome back! Please enter your details.">

        <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
            @csrf

            <x-form.field name="email" label="Email" type="email" placeholder="you@company.com" autofocus />
            <x-form.field name="password" label="Password" type="password" placeholder="••••••••" />

            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2 text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline font-medium">
                    Forgot password?
                </a>
            </div>

            <button type="submit"
                    class="group relative flex w-full items-center justify-center gap-2 h-11 bg-gray-900 text-white font-semibold rounded-lg shadow-sm transition-all duration-200 hover:bg-gray-800 hover:shadow-md active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Log in
            </button>

        </form>

        <div class="relative mt-8">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-xs">
                <span class="bg-white px-3 text-gray-400">or</span>
            </div>
        </div>

        <p class="text-center text-sm text-gray-600 mt-6">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Register</a>
        </p>

        <p class="text-center text-sm text-gray-500 mt-3">
            <a href="/" class="inline-flex items-center gap-1 hover:underline">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Home
            </a>
        </p>

    </x-form>
</x-layout>
