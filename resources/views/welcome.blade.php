<x-layout title="Welcome - HR System">
    <div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-indigo-50 via-white to-gray-100 px-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl ring-1 ring-black/5 p-10 max-w-md w-full text-center">

            <!-- Logo / Icon -->
            <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-600/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.964 0a9 9 0 10-11.964 0m11.964 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">
                Welcome Back
            </h1>

            <p class="text-gray-500 mb-8 text-sm leading-relaxed">
                Please log in or create an account to continue to the HR System.
            </p>

            <div class="flex flex-col gap-3">
                <a href="{{ route('login') }}"
                   class="group relative flex w-full items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-200 hover:bg-gray-800 hover:shadow-md active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Log in
                </a>

                <a href="{{ route('register') }}"
                   class="group relative flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-3 font-semibold text-white shadow-sm transition-all duration-200 hover:bg-indigo-500 hover:shadow-md active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Register
                </a>
            </div>

            <p class="mt-8 text-xs text-gray-400">
                &copy; {{ date('Y') }} HR System. All rights reserved.
            </p>
        </div>
    </div>
</x-layout>
