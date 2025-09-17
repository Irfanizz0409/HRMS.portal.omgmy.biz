<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false 
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Portal OMG') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar (always visible on desktop) -->
        <aside class="hidden lg:flex lg:flex-shrink-0 lg:w-64 bg-gray-900 dark:bg-gray-800 text-white flex-col transition-colors duration-200">
            <div class="p-6">
                <h1 class="text-xl font-bold text-orange-400">Portal OMG</h1>
            </div>
            
            <nav class="mt-6">
                <div class="px-4 space-y-1">
                    <!-- Dashboard -->
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard.admin') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @elseif(auth()->user()->role === 'hr')
                        <a href="{{ route('dashboard.hr') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @elseif(auth()->user()->role === 'staff')
                        <a href="{{ route('dashboard.staff') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @elseif(auth()->user()->role === 'intern')
                        <a href="{{ route('dashboard.intern') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @elseif(auth()->user()->role === 'part_time')
                        <a href="{{ route('dashboard.parttime') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @else
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                    @endif
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Attendance (All Users) -->
                    <a href="{{ route('attendance.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.index', 'attendance.clock', 'attendance.history') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Attendance
                    </a>

                    <!-- Clock In/Out (All Users) -->
                    <a href="{{ route('attendance.clock') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.clock') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Clock In/Out
                    </a>

                    <!-- My Profile (All Users) -->
                    <a href="{{ route('my-profile') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('my-profile') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        My Profile
                    </a>

                    @if(in_array(auth()->user()->role, ['admin', 'hr']))
                    <!-- Divider -->
                    <div class="border-t border-gray-700 my-4"></div>
                    
                    <!-- Admin/HR Only Sections -->
                    <div class="px-2 py-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                    </div>

                    <!-- Employee Management -->
                    <a href="{{ route('employees.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('employees.*') && !request()->routeIs('employees.attendance') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        Employee Management
                    </a>

                    <!-- Attendance Reports -->
                    <a href="{{ route('attendance.admin') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.admin') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                            <path fill-rule="evenodd" d="M3 8a1 1 0 011-1h12a1 1 0 011 1v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm8 1a1 1 0 00-1 1v2a1 1 0 002 0v-2a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Attendance Reports
                    </a>

                    <!-- Work Settings -->
                    <a href="{{ route('admin.work-settings.index') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.work-settings.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        Work Settings
                    </a>
                    @endif
                </div>
            </nav>

            <!-- User Info at Bottom -->
            <div class="absolute bottom-0 w-64 p-4 border-t border-gray-700">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center">
                            <span class="text-xs font-medium text-white">
                                {{ substr(auth()->user()->name, 0, 2) }}
                            </span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 lg:hidden"
             style="display: none;">
            
            <!-- Gray Background -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="sidebarOpen = false"></div>
            
            <!-- Mobile Sidebar -->
            <aside x-show="sidebarOpen"
                   x-transition:enter="transition ease-in-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in-out duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="relative flex flex-col w-64 h-full bg-gray-900 dark:bg-gray-800 text-white">
                
                <div class="p-6 flex justify-between items-center">
                    <h1 class="text-xl font-bold text-orange-400">Portal OMG</h1>
                    <button @click="sidebarOpen = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <nav class="mt-6 flex-1 overflow-y-auto">
                    <div class="px-4 space-y-1">
                        <!-- Mobile Navigation Links (same as desktop) -->
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('dashboard.admin') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @elseif(auth()->user()->role === 'hr')
                            <a href="{{ route('dashboard.hr') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @elseif(auth()->user()->role === 'staff')
                            <a href="{{ route('dashboard.staff') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @elseif(auth()->user()->role === 'intern')
                            <a href="{{ route('dashboard.intern') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @elseif(auth()->user()->role === 'part_time')
                            <a href="{{ route('dashboard.parttime') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @else
                            <a href="{{ route('dashboard') }}" 
                               @click="sidebarOpen = false"
                               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('dashboard.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                        @endif
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            Dashboard
                        </a>

                        <a href="{{ route('attendance.index') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.index', 'attendance.clock', 'attendance.history') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Attendance
                        </a>

                        <a href="{{ route('attendance.clock') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.clock') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Clock In/Out
                        </a>

                        <a href="{{ route('my-profile') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('my-profile') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            My Profile
                        </a>

                        @if(in_array(auth()->user()->role, ['admin', 'hr']))
                        <div class="border-t border-gray-700 my-4"></div>
                        <div class="px-2 py-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
                        </div>

                        <a href="{{ route('employees.index') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('employees.*') && !request()->routeIs('employees.attendance') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                            Employee Management
                        </a>

                        <a href="{{ route('attendance.admin') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('attendance.admin') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a1 1 0 011-1h12a1 1 0 011 1v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm8 1a1 1 0 00-1 1v2a1 1 0 002 0v-2a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            Attendance Reports
                        </a>

                        <a href="{{ route('admin.work-settings.index') }}" 
                           @click="sidebarOpen = false"
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.work-settings.*') ? 'bg-gray-800 text-orange-400' : 'text-gray-300' }}">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                            Work Settings
                        </a>
                        @endif
                    </div>
                </nav>

                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                            <div class="h-8 w-8 rounded-full bg-orange-500 flex items-center justify-center">
                                <span class="text-xs font-medium text-white">
                                    {{ substr(auth()->user()->name, 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" 
                                class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500 mr-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" 
                                class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
                                title="Toggle Dark Mode">
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                            </svg>
                        </button>
                        
                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto p-6 bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>