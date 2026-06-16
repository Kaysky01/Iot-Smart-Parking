<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Parking System - IoT-based real-time parking management dashboard">
    <title>@yield('title', 'Dashboard') — Smart Parking</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Check local storage for dark mode preference BEFORE rendering to prevent flicker
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        } else if (localStorage.getItem('darkMode') === 'false') {
            document.documentElement.classList.remove('dark');
        } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            // Uncomment next line to default to system preference instead of forcing light
            // document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell antialiased">
    <div class="flex h-screen overflow-hidden relative">

        {{-- ===== SIDEBAR OVERLAY ===== --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-20 hidden transition-opacity opacity-0 lg:hidden" onclick="toggleSidebar()"></div>

        {{-- ===== SIDEBAR WRAPPER (Handles push on Desktop) ===== --}}
        <div id="sidebar-wrapper" class="flex-shrink-0 transition-all duration-300 w-0 lg:w-72">
            @include('layouts.sidebar')
        </div>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0 transition-all duration-300">

            {{-- ===== TOPBAR ===== --}}
            @include('layouts.topbar')

            {{-- ===== PAGE CONTENT ===== --}}
            <main class="app-page flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="mx-auto w-full transition-all duration-300" id="main-container">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- ===== TOAST CONTAINER ===== --}}
    <div id="toast-container" class="fixed top-6 right-6 z-[9999] flex flex-col items-end space-y-2"></div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const sidebarWrapper = document.getElementById('sidebar-wrapper');
        const mainContainer = document.getElementById('main-container');

        function toggleSidebar() {
            if (window.innerWidth >= 1024) {
                // Desktop logic: Adjust the wrapper's width and slide the sidebar itself
                if (sidebarWrapper.classList.contains('lg:w-72')) {
                    // Close desktop
                    sidebarWrapper.classList.remove('lg:w-72');
                    sidebarWrapper.classList.add('lg:w-0');
                    sidebar.classList.add('lg:-translate-x-full');
                    sidebar.classList.remove('lg:translate-x-0');

                    // Allow container to expand wider when sidebar is closed
                    mainContainer.classList.remove('max-w-7xl');
                    mainContainer.classList.add('max-w-screen-2xl');
                } else {
                    // Open desktop
                    sidebarWrapper.classList.remove('lg:w-0');
                    sidebarWrapper.classList.add('lg:w-72');
                    sidebar.classList.remove('lg:-translate-x-full');
                    sidebar.classList.add('lg:translate-x-0');

                    // Constrain container width when sidebar is open to maintain original look
                    mainContainer.classList.remove('max-w-screen-2xl');
                    mainContainer.classList.add('max-w-7xl');
                }
            } else {
                // Mobile logic: Slide out the absolute sidebar and show overlay
                sidebar.classList.toggle('-translate-x-full');

                if (sidebar.classList.contains('-translate-x-full')) {
                    overlay.classList.add('opacity-0');
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                    }, 300);
                } else {
                    overlay.classList.remove('hidden');
                    setTimeout(() => {
                        overlay.classList.remove('opacity-0');
                    }, 10);
                }
            }
        }

        // Initialize state on load
        function initSidebar() {
            if (window.innerWidth >= 1024) {
                // Desktop Default: Open
                sidebarWrapper.classList.add('lg:w-72');
                sidebarWrapper.classList.remove('lg:w-0');
                sidebar.classList.add('lg:translate-x-0');
                sidebar.classList.remove('lg:-translate-x-full', '-translate-x-full');
                overlay.classList.add('hidden', 'opacity-0');

                mainContainer.classList.add('max-w-7xl');
                mainContainer.classList.remove('max-w-screen-2xl');
            } else {
                // Mobile Default: Closed
                sidebarWrapper.classList.remove('lg:w-72');
                sidebarWrapper.classList.add('lg:w-0');
                sidebar.classList.add('-translate-x-full', 'lg:-translate-x-full');
                sidebar.classList.remove('lg:translate-x-0');
                overlay.classList.add('hidden', 'opacity-0');

                mainContainer.classList.add('max-w-7xl');
                mainContainer.classList.remove('max-w-screen-2xl');
            }
        }

        window.addEventListener('resize', initSidebar);
        document.addEventListener('DOMContentLoaded', initSidebar);
    </script>

    @stack('scripts')
</body>
</html>
