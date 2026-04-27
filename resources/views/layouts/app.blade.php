<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SpendWise') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="/favicon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ mobileSidebarOpen: false }">
        <x-banner />

        <div class="flex min-h-screen">
            <!-- Desktop Sidebar -->
            <aside class="w-64 bg-navy-950 text-white hidden lg:flex flex-col sticky top-0 h-screen shadow-2xl z-50 shrink-0">
                <div class="py-4 px-6 flex flex-col items-center border-b border-navy-800 shrink-0">
                    <div class="size-12 bg-white rounded-full flex items-center justify-center shadow-xl p-2 mb-2 overflow-hidden">
                        <img src="/logo-icon.png" alt="SpendWise Logo" class="size-full object-contain">
                    </div>
                    <span class="text-base font-bold tracking-tight text-white font-outfit uppercase">Spend<span class="text-gold-500">Wise</span></span>
                </div>
                
                <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                    <div class="px-4 mb-3">
                        <span class="text-[10px] font-bold text-navy-400 uppercase tracking-[0.2em]">Menu</span>
                    </div>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="font-semibold text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('transactions') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('transactions') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold text-sm">Transactions</span>
                    </a>

                    <a href="{{ route('budgets') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('budgets') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-semibold text-sm">Budgets</span>
                    </a>

                    <div class="px-4 mt-4 mb-3">
                        <span class="text-[10px] font-bold text-navy-400 uppercase tracking-[0.2em]">Management</span>
                    </div>

                    <a href="{{ route('accounts') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('accounts') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span class="font-semibold text-sm">Accounts</span>
                    </a>

                    <a href="{{ route('recurring') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('recurring') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span class="font-semibold text-sm">Recurring</span>
                    </a>

                    <a href="{{ route('categories') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('categories') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01M4 21h16a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="font-semibold text-sm">Categories</span>
                    </a>

                    <a href="{{ route('reports') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('reports') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32 4H1c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h11l2 2h9c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                        <span class="font-semibold text-sm">Reports</span>
                    </a>

                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-2xl transition-all {{ request()->routeIs('profile.show') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-semibold text-sm">Settings</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-navy-800 shrink-0">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" @click.prevent="$root.submit();" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition-all">
                            <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span class="font-bold text-sm uppercase tracking-widest">Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileSidebarOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-navy-950/60 backdrop-blur-sm z-[60] lg:hidden"
                 @click="mobileSidebarOpen = false"></div>

            <!-- Mobile Sidebar Drawer -->
            <aside x-show="mobileSidebarOpen"
                   x-transition:enter="transition ease-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full"
                   class="fixed inset-y-0 left-0 w-[280px] bg-navy-950 text-white flex flex-col z-[70] lg:hidden shadow-2xl">
                <div class="py-6 px-6 flex items-center justify-between border-b border-navy-800">
                    <div class="flex items-center space-x-3">
                        <div class="size-10 bg-white rounded-full flex items-center justify-center p-1.5 overflow-hidden">
                            <img src="/logo-icon.png" alt="SpendWise Logo" class="size-full object-contain">
                        </div>
                        <span class="text-base font-bold tracking-tight text-white font-outfit uppercase">Spend<span class="text-gold-500">Wise</span></span>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="p-2 text-navy-400 hover:text-white transition">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        <span class="font-semibold text-base">Dashboard</span>
                    </a>

                    <a href="{{ route('transactions') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('transactions') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-semibold text-base">Transactions</span>
                    </a>

                    <a href="{{ route('budgets') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('budgets') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        <span class="font-semibold text-base">Budgets</span>
                    </a>

                    <a href="{{ route('accounts') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('accounts') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span class="font-semibold text-base">Accounts</span>
                    </a>

                    <a href="{{ route('recurring') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('recurring') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span class="font-semibold text-base">Recurring</span>
                    </a>

                    <a href="{{ route('categories') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('categories') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 11h.01M7 15h.01M13 7h.01M13 11h.01M13 15h.01M17 7h.01M17 11h.01M17 15h.01M4 21h16a2 2 0 002-2V5a2 2 0 00-2-2H4a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        <span class="font-semibold text-base">Categories</span>
                    </a>

                    <a href="{{ route('reports') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('reports') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m32 4H1c-1.1 0-2-.9-2-2V5c0-1.1.9-2 2-2h11l2 2h9c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path></svg>
                        <span class="font-semibold text-base">Reports</span>
                    </a>

                    <a href="{{ route('profile.show') }}" class="flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('profile.show') ? 'bg-gold-700 text-white shadow-lg' : 'text-navy-300 hover:bg-navy-900 hover:text-white' }}">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-semibold text-base">Settings</span>
                    </a>
                </nav>

                <div class="p-4 border-t border-navy-800">
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf
                        <button type="submit" @click.prevent="$root.submit();" class="w-full flex items-center justify-center space-x-3 px-6 py-4 rounded-2xl bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all font-bold uppercase tracking-widest text-xs group">
                            <svg class="size-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Topbar (Mobile Sidebar Toggle + User Profile) -->
                <header class="bg-white border-b border-gray-100 min-h-[5rem] py-4 sm:py-0 flex items-center sticky top-0 z-40 px-4 sm:px-8">
                    <button @click="mobileSidebarOpen = true" class="lg:hidden p-2 text-navy-950 mr-2 sm:mr-4 hover:bg-gray-100 rounded-xl transition">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <div class="flex-1 flex justify-between items-center">
                        <div class="flex flex-col gap-0.5 min-w-0">
                            @if (isset($header))
                                <div class="">
                                    {{ $header }}
                                </div>
                            @endif
                            <!-- Live Time Clock -->
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <svg class="size-3.5 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span id="live-time" class="text-[10px] font-bold text-gold-600 uppercase tracking-widest tabular-nums"></span>
                            </div>
                        </div>
                        
                            <div class="flex items-center space-x-2 sm:space-x-6">
                                @livewire('notification-dropdown')

                                <!-- User Info -->
                                <div class="hidden md:flex flex-col items-end border-r border-gray-100 pr-6">
                                    <span class="text-sm font-bold text-navy-950 leading-none mb-1">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] font-bold text-navy-400 uppercase tracking-widest leading-none">{{ Auth::user()->email }}</span>
                                </div>
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gold-500 transition shadow-sm shrink-0">
                                            <img class="size-8 sm:size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    </x-slot>
                                <x-slot name="content">
                                    <div class="block px-4 py-2 text-xs text-gray-400 uppercase tracking-widest font-bold">Manage Account</div>
                                    <x-dropdown-link href="{{ route('profile.show') }}">Profile Settings</x-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();" class="text-red-600 font-bold">Logout</x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 sm:px-0">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        @stack('scripts')

        <!-- Live Clock Script -->
        <script>
            (function () {
                function pad(n) { return String(n).padStart(2, '0'); }

                function tick() {
                    const now  = new Date();
                    const hrs  = now.getHours();
                    const ampm = hrs >= 12 ? 'PM' : 'AM';
                    const time = `${pad(hrs % 12 || 12)}:${pad(now.getMinutes())}:${pad(now.getSeconds())} ${ampm}`;
                    const el   = document.getElementById('live-time');
                    if (el) el.textContent = time;
                }

                tick();
                setInterval(tick, 1000);
            })();
        </script>
        
        @livewire('ai-chat-assistant')
    </body>
</html>
