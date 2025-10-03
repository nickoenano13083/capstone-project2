<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        @livewireStyles
        @stack('styles')
        <style>
            /* Base styles */
            .nav-item:hover {
                background-color: rgba(255, 255, 255, 0.1) !important;
                transform: translateX(4px);
            }
            
            .sidebar-bg {
                background: linear-gradient(180deg, #233c66 0%, #1a2d4a 100%);
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
            }

            /* Menu button */
            .mobile-menu-button {
                position: fixed;
                top: 1.25rem;
                left: 1.25rem;
                z-index: 60;
                background: rgba(35, 60, 102, 0.9);
                backdrop-filter: blur(5px);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                width: 2.75rem;
                height: 2.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .mobile-menu-button:hover {
                background: rgba(35, 60, 102, 1);
                transform: scale(1.05);
            }

            .mobile-menu-button:active {
                transform: scale(0.95);
            }

            /* Theme toggle mobile button */
            .theme-toggle-mobile {
                position: fixed;
                top: 1.25rem;
                right: 1.25rem;
                z-index: 60;
                background: rgba(35, 60, 102, 0.9);
                backdrop-filter: blur(5px);
                color: white;
                border: 2px solid rgba(255, 255, 255, 0.1);
                border-radius: 8px;
                width: 2.75rem;
                height: 2.75rem;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .theme-toggle-mobile:hover {
                background: rgba(35, 60, 102, 1);
                transform: scale(1.05);
            }

            .theme-toggle-mobile:active {
                transform: scale(0.95);
            }

            /* Sidebar styles */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 280px;
                transform: translateX(-100%);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 50;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
            }

            .sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 3px;
            }

            .sidebar.active {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
            }

            /* Overlay */
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
                z-index: 40;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .overlay.active {
                display: block;
                opacity: 1;
            }

            /* Main content */
            .main-content {
                padding-top: 0.8rem; /* Space for the menu button */
                transition: margin 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
            }

            /* Responsive styles */
            @media (min-width: 1024px) {
                .mobile-menu-button {
                    left: 1.5rem;
                }

                .sidebar {
                    transform: translateX(0);
                    width: 280px;
                }

                .sidebar:not(.active) {
                    transform: translateX(-100%);
                }

                .sidebar.active + .main-content {
                    margin-left: 280px;
                }

                .sidebar:not(.active) + .main-content {
                    margin-left: 0;
                }

                .mobile-menu-button {
                    left: 1.5rem;
                }

                .sidebar:not(.active) + .main-content .mobile-menu-button {
                    left: 1.5rem;
                }
            }

            @media (max-width: 1023px) {
                .sidebar.active + .main-content {
                    margin-left: 0;
                }
                
                /* Mobile sidebar fixes */
                .sidebar {
                    height: 100vh;
                    height: -webkit-fill-available;
                    max-height: 100vh;
                    position: fixed;
                    top: 0;
                    left: 0;
                    z-index: 50;
                }
                
                .sidebar.active {
                    height: 100vh;
                    height: -webkit-fill-available;
                    max-height: 100vh;
                    overflow-y: auto !important;
                    -webkit-overflow-scrolling: touch;
                }
                
                /* Ensure logout button is visible and accessible */
                .sidebar.active nav ul {
                    padding-bottom: 2rem; /* Add space at bottom for logout button */
                }
                
                .sidebar.active .mt-auto {
                    margin-top: auto !important;
                    position: sticky;
                    bottom: 0;
                    background: linear-gradient(180deg, transparent 0%, rgba(35, 60, 102, 0.9) 20%);
                    padding-top: 1rem;
                    padding-bottom: 1rem;
                    margin-bottom: 0;
                }
                
                /* Mobile overlay improvements */
                .overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    backdrop-filter: blur(2px);
                    z-index: 40;
                }
                
                .overlay.active {
                    display: block;
                }
            }

            /* Navigation items */
            .nav-item {
                transition: all 0.2s ease;
                margin: 0.25rem 0.75rem;
                border-radius: 0.5rem;
            }

            .nav-item.active {
                background: rgba(255, 255, 255, 0.1);
            }

            .nav-item i {
                transition: transform 0.2s ease;
            }

            .nav-item:hover i {
                transform: scale(1.1);
            }

            /* Logo and header */
            .sidebar-header {
                transition: all 0.3s ease;
                padding: 1.5rem 1rem;
            }

            .sidebar-logo {
                transition: all 0.3s ease;
                width: 80px;
                height: 80px;
            }

            .sidebar:not(.active) .sidebar-header {
                padding: 1rem 0.5rem;
            }

            .sidebar:not(.active) .sidebar-logo {
                width: 50px;
                height: 50px;
            }
        </style>
    </head>
    <body class="font-sans antialiased" id="app-body">
        <button class="mobile-menu-button" id="mobileMenuButton" aria-label="Toggle menu">
            <i class="fas fa-bars text-xl"></i>
        </button>
       
        <div class="min-h-screen bg-gray-100 flex">
            {{-- Overlay --}}
            <div class="overlay" id="overlay"></div>
            
            {{-- Sidebar --}}
            <aside class="sidebar-bg text-white flex flex-col min-h-screen sidebar" id="sidebar">
                {{-- Logo and Header Section --}}
                <div class="sidebar-header flex-shrink-0 flex flex-col items-center">
                    <div class="sidebar-logo rounded-full flex items-center justify-center mb-4 border-4 border-slate-600 overflow-hidden">
                        <img src="{{ asset('jil-logo.png') }}" alt="Logo" class="h-full w-auto filter brightness-0 invert">
                    </div>
                    <h1 class="text-xl font-bold text-white mb-1 truncate max-w-full px-2 text-center">Church Dashboard</h1>
                    <p class="text-sm text-slate-300 truncate max-w-full px-2 text-center">Welcome, {{ auth()->user()->name ?? 'User' }}</p>
                    @php($chapterName = auth()->user()?->member?->chapter?->name ?? auth()->user()?->preferredChapter?->name)
                    @if($chapterName)
                        <p class="text-xs text-slate-300 mb-3 truncate max-w-full px-2 text-center">Chapter: {{ $chapterName }}</p>
                    @else
                        <p class="text-xs text-slate-300 mb-3">Chapter: —</p>
                    @endif
                    @auth
                        @php($role = auth()->user()->role ?: 'User')
                        {{-- The linter incorrectly flags these colors as the same, but they are different in the browser --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if($role === 'Admin')
                                    bg-red-500 text-white
                            @elseif($role === 'Leader')
                                bg-orange-500 text-white
                            @elseif($role === 'Member')
                                bg-green-500 text-white
                            @endif">
                            @if($role === 'Admin')
                                <svg class="h-3 w-3 text-white mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @elseif($role === 'Leader')
                                <svg class="h-3 w-3 text-white mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @elseif($role === 'Member')
                                <svg class="h-3 w-3 text-white mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            @endif
                            {{ $role }}
                        </span>
                    @endauth
                </div>
                
                {{-- Divider --}}
                <div class="flex-shrink-0 border-t border-slate-500 mx-4"></div>
                
                {{-- Navigation Menu --}}
                <nav class="flex-1 py-4">
                    <ul class="space-y-1 px-4">
                        <li>
                            <a href="{{ route('dashboard') }}"
                               style="transition: background 0.2s;" onmouseover="this.style.background='rgb(105,127,176)'" onmouseout="this.style.background=''"
                               class="flex items-center px-4 py-3 rounded-lg text-white font-medium {{ request()->routeIs('dashboard') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-home w-5 text-center mr-3 {{ request()->routeIs('dashboard') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('dashboard') ? 'text-slate-800' : '' }}">Dashboard</span>
                            </a>
                        </li>
                        @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                        <li>
                            <a href="{{ route('members.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('members.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-user-friends w-5 text-center mr-3 {{ request()->routeIs('members.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('members.*') ? 'text-slate-800' : '' }}">Members</span>
                            </a>
                        </li>
                        @endif
                        <li>
                            <a href="{{ route('events.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('events.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-calendar-alt w-5 text-center mr-3 {{ request()->routeIs('events.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('events.*') ? 'text-slate-800' : '' }}">Events</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('prayer-requests.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('prayer-requests.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-hands-praying w-5 text-center mr-3 {{ request()->routeIs('prayer-requests.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('prayer-requests.*') ? 'text-slate-800' : '' }}">Prayer Requests</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('messages.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('messages.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-comments w-5 text-center mr-3 {{ request()->routeIs('messages.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('messages.*') ? 'text-slate-800' : '' }}">Messages</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('attendance.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('attendance.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-calendar-check w-5 text-center mr-3 {{ request()->routeIs('attendance.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('attendance.*') ? 'text-slate-800' : '' }}">Attendance</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('map') }}"
                               style="transition: background 0.2s;" onmouseover="this.style.background='rgb(105,127,176)'" onmouseout="this.style.background=''"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('map') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-map-marker-alt w-5 text-center mr-3 {{ request()->routeIs('map') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('map') ? 'text-slate-800' : '' }}">Map</span>
                            </a>
                        </li>
                        @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                        <li>
                            <a href="{{ route('chapters.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('chapters.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-project-diagram w-5 text-center mr-3 {{ request()->routeIs('chapters.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('chapters.*') ? 'text-slate-800' : '' }}">Chapters</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                        <li>
                            <a href="{{ route('invitations.index') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('invitations.*') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-envelope w-5 text-center mr-3 {{ request()->routeIs('invitations.*') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('invitations.*') ? 'text-slate-800' : '' }}">Invitations Code</span>
                            </a>
                        </li>
                        @endif
                        @if(in_array(auth()->user()->role, ['Admin', 'Leader']))
                        <li>
                            <a href="{{ route('admin.activity-log') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.activity-log') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-list w-5 text-center mr-3 {{ request()->routeIs('admin.activity-log') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('admin.activity-log') ? 'text-slate-800' : '' }}">Activity Log</span>
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->role === 'Member')
                        <li>
                            <a href="{{ route('profile.my-qr-code') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('profile.my-qr-code') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-qrcode w-5 text-center mr-3 {{ request()->routeIs('profile.my-qr-code') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('profile.my-qr-code') ? 'text-slate-800' : '' }}">My QR Code</span>
                            </a>
                        </li>
                        @endif
                        {{-- Analytics link hidden intentionally --}}
                        <li>
                            <a href="{{ route('profile.edit') }}"
                               class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200 {{ request()->routeIs('profile.edit') ? 'bg-white text-slate-800' : '' }}"
                            >
                                <i class="fas fa-cog w-5 text-center mr-3 {{ request()->routeIs('profile.edit') ? 'text-slate-800' : '' }}"></i>
                                <span class="{{ request()->routeIs('profile.edit') ? 'text-slate-800' : '' }}">Settings</span>
                            </a>
                        </li>
                        {{-- Logout Button --}}
                        <li class="mt-auto">
                            @if(session()->has('impersonated_by'))
                                <a href="{{ route('admin.users.stopImpersonate') }}" 
                                   class="nav-item flex items-center px-4 py-3 rounded-lg text-slate-200 hover:text-white transition-colors duration-200">
                                    <i class="fas fa-user-secret w-5 text-center mr-3"></i>
                                    <span>Stop Impersonating</span>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-3 rounded-lg text-white bg-red-600 hover:bg-red-700 transition-colors duration-200">
                                    <i class="fas fa-sign-out-alt w-5 text-center mr-3"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </nav>
            </aside>
            
            {{-- Main Content --}}
            <div class="main-content flex-1 flex flex-col min-h-screen">

                <!-- Page Heading -->
                @if(isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1">
                    @yield('content', $slot ?? '')
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @livewireScripts
        @stack('scripts')
        <style>
            [data-tooltip] {
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
            
            [data-tooltip]::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                padding: 4px 8px;
                background-color: #1F2937;
                color: white;
                border-radius: 4px;
                font-size: 12px;
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.2s, visibility 0.2s;
                z-index: 10;
                margin-bottom: 5px;
                pointer-events: none;
            }
            
            [data-tooltip]:hover::after {
                opacity: 1;
                visibility: visible;
            }
            
            [data-tooltip]::before {
                content: '';
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                border-width: 5px;
                border-style: solid;
                border-color: #1F2937 transparent transparent transparent;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.2s, visibility 0.2s;
                margin-bottom: -5px;
            }
            
            [data-tooltip]:hover::before {
                opacity: 1;
                visibility: visible;
            }
        </style>
        <script>
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            mobileMenuButton.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            });

            // Theme toggle functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Load theme from localStorage
                let isDark = localStorage.getItem('dashboardTheme') === 'dark';
                // Helper to set theme
                function setTheme(isDark) {
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                        const themeIcon = document.getElementById('theme-icon-mobile');
                        if (themeIcon) themeIcon.className = 'fas fa-sun';
                    } else {
                        document.documentElement.classList.remove('dark');
                        const themeIcon = document.getElementById('theme-icon-mobile');
                        if (themeIcon) themeIcon.className = 'fas fa-moon';
                    }
                }
                setTheme(isDark);

            });

            // Global theme toggle function
            function toggleTheme() {
                let isDark = document.documentElement.classList.contains('dark');
                let newTheme = !isDark;
                localStorage.setItem('dashboardTheme', newTheme ? 'dark' : 'light');
                
                if (newTheme) {
                    document.documentElement.classList.add('dark');
                    const themeIcon = document.getElementById('theme-icon-mobile');
                    if (themeIcon) themeIcon.className = 'fas fa-sun';
                } else {
                    document.documentElement.classList.remove('dark');
                    const themeIcon = document.getElementById('theme-icon-mobile');
                    if (themeIcon) themeIcon.className = 'fas fa-moon';
                }
            }
        </script>
    </body>
</html>
