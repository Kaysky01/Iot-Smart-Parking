<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Parking System - IoT-based real-time parking management dashboard">
    <title>@yield('title', 'Dashboard') — Smart Parking IoT</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">

        {{-- ===== SIDEBAR ===== --}}
        @include('layouts.sidebar')

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">

            {{-- ===== TOPBAR ===== --}}
            @include('layouts.topbar')

            {{-- ===== PAGE CONTENT ===== --}}
            <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-slate-900/50">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- ===== TOAST CONTAINER ===== --}}
    <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col items-end space-y-2"></div>

    @stack('scripts')
</body>
</html>
