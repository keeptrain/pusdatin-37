<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @livewireStyles
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 p-0">

    <header class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 shadow-sm">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">

                <!-- Logo -->
                <div class="flex items-center">
                    <flux:sidebar.toggle class="lg:hidden mr-3" icon="bars-2" inset="left" />

                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2" wire:navigate>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">Pusdatin</span>
                    </a>
                </div>


                <nav class="hidden lg:flex items-center space-x-1">
                    <!-- Home -->
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                        wire:navigate>
                        Home
                    </a>

                    <!-- Ajukan Permohonan Dropdown -->
                    <div class="relative group">
                        <button class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors duration-200 {{ request()->routeIs('permohonan.*') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                            Ajukan Permohonan
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 mt-1 w-64 bg-white dark:bg-zinc-800 rounded-md shadow-lg border border-gray-200 dark:border-zinc-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-1 group-hover:translate-y-0 z-50">
                            <div class="py-2">
                                <a href=""
                                    class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 {{ request()->routeIs('permohonan.sistem-informasi') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                                    wire:navigate>
                                    <div class="font-medium">Permohonan Sistem Informasi & Data</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ajukan permohonan untuk sistem informasi dan data</div>
                                </a>
                                <a href=""
                                    class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 {{ request()->routeIs('permohonan.kehumasan') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                                    wire:navigate>
                                    <div class="font-medium">Permohonan Kehumasan</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Ajukan permohonan untuk kehumasan</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- History Permohonan -->
                    <a href="{{ route('history') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors duration-200 {{ request()->routeIs('history') ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : '' }}"
                        wire:navigate>
                        History Permohonan
                    </a>
                </nav>


                <div class="flex items-center space-x-2">
                    <!-- Notifications -->
                    <flux:modal.trigger name="notifications-user">
                        <flux:tooltip :content="__('Notifications')" position="bottom">
                            <button class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors duration-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.25A3.75 3.75 0 0114.25 6v1.5l2.5 2.5v3.5a.75.75 0 01-.75.75H7.75a.75.75 0 01-.75-.75v-3.5l2.5-2.5V6a3.75 3.75 0 014.32-3.75z" />
                                </svg>
                            </button>
                        </flux:tooltip>
                    </flux:modal.trigger>

                    <!-- User Menu -->
                    <flux:dropdown position="bottom" align="end">
                        <button class="flex items-center space-x-2 p-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition-colors duration-200">
                            <div class="h-8 w-8 bg-blue-600 dark:bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                {{ auth()->user()->initials() }}
                            </div>
                            <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <flux:menu>
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-700">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 bg-blue-600 dark:bg-blue-500 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ auth()->user()->initials() }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>

                            <flux:menu.separator />

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full text-red-600 dark:text-red-400">
                                    {{ __('Log Out') }}
                                </flux:menu.item>
                            </form>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
    </header>


    <flux:modal name="notifications-user" variant="flyout" position="right" :closable="false" class="md:w-96">
        <livewire:admin.notifications />
    </flux:modal>

    <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <div class="p-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 mb-6" wire:navigate>
                <span class="text-xl font-bold text-blue-600 dark:text-blue-400">Pusdatin</span>
            </a>
        </div>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Menu')">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    Home
                </flux:navlist.item>

                <!-- Mobile Permohonan Menu -->
                <flux:navlist.item icon="document-plus" class="cursor-pointer">
                    <details class="w-full">
                        <summary class="flex items-center justify-between w-full py-2">
                            <span>Ajukan Permohonan</span>
                            <svg class="h-4 w-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </summary>
                        <div class="mt-2 ml-4 space-y-1">
                            <a href=""
                                class="block py-2 px-3 text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                                wire:navigate>
                                Sistem Informasi & Data
                            </a>
                            <a href=""
                                class="block py-2 px-3 text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 rounded-md hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors duration-200"
                                wire:navigate>
                                Kehumasan
                            </a>
                        </div>
                    </details>
                </flux:navlist.item>

                <flux:navlist.item icon="clock" :href="route('history')" :current="request()->routeIs('history')" wire:navigate>
                    History Permohonan
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />
    </flux:sidebar>

    <div class="max-w-[1440px] mx-auto">
        {{ $slot }}
    </div>

    @livewireScripts
    @fluxScripts
</body>

</html>