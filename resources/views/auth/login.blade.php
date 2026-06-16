<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Parking System - Login to Admin Dashboard">
    <title>Login — Smart Parking</title>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else if (localStorage.getItem('darkMode') === 'false') {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell min-h-screen flex items-center justify-center p-4 antialiased relative">

    {{-- Clean Soft Background Decor --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[var(--app-primary-softer)] rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[var(--app-info-soft)] rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-[var(--app-primary)] shadow-lg shadow-[color-mix(in_srgb,var(--app-primary)_30%,transparent)] mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-[var(--app-text)] tracking-tight">Smart Parking</h1>
            <p class="text-[var(--app-primary)] text-sm font-semibold tracking-wider uppercase mt-1">IoT Parking System</p>
        </div>

        {{-- Login Card --}}
        <div class="app-card p-8 sm:p-10">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-[var(--app-text)]">Welcome back</h2>
                <p class="text-[var(--app-text-muted)] text-sm mt-1">Sign in to access the dashboard</p>
            </div>

            {{-- Error Messages --}}
            @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-[var(--app-danger-soft)] border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)]">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[var(--app-danger)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[var(--app-danger)] text-sm font-medium">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif

            @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-[var(--app-danger-soft)] border border-[color-mix(in_srgb,var(--app-danger)_20%,transparent)]">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[var(--app-danger)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[var(--app-danger)] text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-[var(--app-text-secondary)] mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 app-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                               class="app-input pl-11"
                               placeholder="admin@parking.local">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-[var(--app-text-secondary)] mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 app-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required
                               class="app-input pl-11"
                               placeholder="••••••••">
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" id="remember"
                               class="w-4 h-4 rounded border-[var(--app-border)] bg-[var(--app-surface)] text-[var(--app-primary)] focus:ring-[var(--app-primary-soft)] focus:ring-offset-0">
                        <span class="text-sm font-medium text-[var(--app-text-secondary)]">Remember me</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" id="login-btn"
                            class="app-button-primary w-full py-3.5 px-6 font-bold text-sm tracking-wide">
                        Sign In
                    </button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>
