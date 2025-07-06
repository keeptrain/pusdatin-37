<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @livewireStyles
    @include('partials.head')
    <style>
        .simple-navbar {
            background: white;
            border: 1px solid rgba(229, 231, 235, 1);
        }

        .dark .simple-navbar {
            background: rgb(30, 41, 59);
            border: 1px solid rgba(51, 65, 85, 1);
        }

        .nav-item-glass {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .nav-item-glass:hover {
            background: rgba(241, 245, 249, 1);
            transform: translateY(-1px);
        }

        .dark .nav-item-glass:hover {
            background: rgba(51, 65, 85, 1);
        }

        .dropdown-glass {
            background: white;
            border: 1px solid rgba(229, 231, 235, 1);
        }

        .dark .dropdown-glass {
            background: rgb(30, 41, 59);
            border: 1px solid rgba(51, 65, 85, 1);
        }

        .logo-jakreq {
            background: linear-gradient(135deg, #364872, #1e293b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 800;
        }

        .active-nav-glass {
            background: rgba(54, 72, 114, 0.1);
            color: #364872;
        }

        .dark .active-nav-glass {
            background: rgba(54, 72, 114, 0.3);
            color: #94a3b8;
        }

        /* Floating navbar positioning - only on desktop */
        .floating-navbar {
            position: fixed;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            z-index: 50;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Hide floating navbar on mobile when sidebar is used */
        @media (max-width: 1023px) {
            .floating-navbar {
                display: none;
            }
        }

        /* Content offset for floating navbar - only on desktop */
        .content-offset {
            padding-top: 6rem;
        }

        @media (max-width: 1023px) {
            .content-offset {
                padding-top: 0;
            }
        }

        /* Mobile navbar - simple top bar for mobile */
        .mobile-navbar {
            display: none;
            background: white;
            border-bottom: 1px solid rgba(229, 231, 235, 1);
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .dark .mobile-navbar {
            background: rgb(30, 41, 59);
            border-bottom: 1px solid rgba(51, 65, 85, 1);
        }

        @media (max-width: 1023px) {
            .mobile-navbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        }

        /* Custom center layout styles */
        .navbar-center-layout {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 1.5rem;
        }

        .navbar-center-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }

        @media (max-width: 1023px) {
            .navbar-center-menu {
                display: none;
            }
        }

        .nav-menu-item {
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: rgb(51 65 85);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dark .nav-menu-item {
            color: rgb(226 232 240);
        }

        .nav-menu-item:hover {
            background: rgba(241, 245, 249, 1);
            transform: translateY(-1px);
        }

        .dark .nav-menu-item:hover {
            background: rgba(51, 65, 85, 1);
        }

        .nav-menu-item.active {
            background: rgba(54, 72, 114, 0.1);
            color: #364872;
        }

        .dark .nav-menu-item.active {
            background: rgba(54, 72, 114, 0.3);
            color: #94a3b8;
        }

        .dropdown-menu-glass {
            position: absolute;
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
            background: white;
            border: 1px solid rgba(229, 231, 235, 1);
            border-radius: 0.75rem;
            padding: 0.5rem;
            min-width: 300px;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .dark .dropdown-menu-glass {
            background: rgb(30, 41, 59);
            border: 1px solid rgba(51, 65, 85, 1);
        }

        .dropdown-wrapper:hover .dropdown-menu-glass {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            color: rgb(51 65 85);
            margin-bottom: 0.25rem;
        }

        .dark .dropdown-item {
            color: rgb(226 232 240);
        }

        .dropdown-item:hover {
            background: rgba(241, 245, 249, 1);
        }

        .dark .dropdown-item:hover {
            background: rgba(51, 65, 85, 1);
        }

        .dropdown-item:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body x-data="dashboard" class="min-h-screen bg-white dark:bg-zinc-800 p-0">
    @if (session('status'))
    @php
    $variant = session('status')['variant'];
    $message = session('status')['message'];
    @endphp
    <flux:notification.toast :variant="$variant" :message="$message" />
    @endif

    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:navbar.item>
            <div class="relative">
                <flux:navbar.item @mouseenter="openDropdown = true" icon="folder-open" icon:trailing="chevron-down" :current="request()->routeIs('si-data.form') || request()->routeIs('pr.form')">
                    {{ __('Ajukan Permohonan') }}
                </flux:navbar.item>

                <!-- Dropdown Menu -->
                <x-menu.dropdown-menu-on-dashboard-user />
            </div>
            <flux:navbar.item icon="book-open-text" :href="route('list.request')" :current="request()->routeIs('list.request') || request()->routeIs('detail.request')" wire:navigate>
                {{ __('Daftar Permohonan') }}
            </flux:navbar.item>
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
            @php
            $hasUnread = auth()->user()->unreadNotifications()->whereNull('read_at')->exists();
            $icon = $hasUnread ? 'bell-alert' : 'bell';
            @endphp
            <flux:modal.trigger name="notifications-user">
                <flux:tooltip :content="__('Notifikasi')" position="bottom">
                    <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" :icon="$icon" target="_blank" :iconDot="$hasUnread"
                        :label="__('Notifikasi')" />
                </flux:tooltip>
            </flux:modal.trigger>

            <flux:modal name="notifications-user" variant="flyout" position="right" :closable="false" class="md:w-96">
                <livewire:admin.notifications :needCount="false" />
            </flux:modal>
        </flux:navbar>

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg">
                <!-- User Info Section -->
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal border-b border-slate-300 dark:border-slate-600">
                        <div class="flex items-center gap-2 px-4 py-3 text-left text-sm">
                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-gradient-to-br from-[#364872] to-slate-600 text-white font-semibold">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold text-slate-700 dark:text-white">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs text-slate-600 dark:text-slate-400">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator class="border-slate-300 dark:border-slate-600" />

                <!-- Settings -->
                <flux:menu.radio.group>
                    <flux:menu.item
                        :href="route('settings.profile')"
                        icon="cog"
                        wire:navigate
                        class="text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700">
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator class="border-slate-300 dark:border-slate-600" />

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item
                        as="button"
                        type="submit"
                        icon="arrow-right-start-on-rectangle"
                        class="w-full text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-700">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
        </div>
        </div>

        <!-- Mobile Notification Modal -->
        <flux:modal name="notifications-user" variant="flyout" position="right" :closable="false" class="md:w-96 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
            <livewire:admin.notifications :dashboardUser="false" />
        </flux:modal>

        <!-- Floating Header -->
        <div class="floating-navbar">
            <nav class="simple-navbar rounded-2xl shadow-lg">
                <div class="navbar-center-layout">
                    <!-- Bagian Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#364872] to-slate-600 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="logo-jakreq text-xl font-bold">Jakreq</span>
                    </div>

                    <!-- Center Navigation Menu -->
                    <div class="navbar-center-menu">
                        <!-- Home -->
                        <a href="{{ route('dashboard') }}"
                            wire:navigate
                            class="nav-menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Home
                        </a>

                        <!-- Ajukan Permohonan with Dropdown -->
                        <div class="dropdown-wrapper relative">
                            <button class="nav-menu-item">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                                Ajukan Permohonan
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div class="dropdown-menu-glass">
                                <a href="{{ route('si-data.form') }}"
                                    wire:navigate
                                    class="dropdown-item">
                                    <div class="w-8 h-8 rounded-lg bg-blue-500 bg-opacity-20 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-700 dark:text-white">Sistem Informasi & Data</div>
                                        <div class="text-xs text-slate-600 dark:text-white opacity-70">Pengajuan permohonan layanan SI & data</div>
                                    </div>
                                </a>

                                <a href="{{ route('pr.form') }}"
                                    wire:navigate
                                    class="dropdown-item">
                                    <div class="w-8 h-8 rounded-lg bg-purple-500 bg-opacity-20 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-700 dark:text-white">Kehumasan</div>
                                        <div class="text-xs text-slate-600 dark:text-white opacity-70">Pengajuan permohonan layanan kehumasan</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Histori Permohonan -->
                        <a href="{{ route('list.request') }}"
                            wire:navigate
                            class="nav-menu-item {{ (request()->routeIs('list.request') || request()->routeIs('detail.request')) ? 'active' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Histori Permohonan
                        </a>
                    </div>

                    <!-- Right Section (User Profile) -->
                    <div class="flex items-center space-x-2">
                        <!-- Notifications -->

                        <flux:modal.trigger name="notifications-user-desktop">
                            <flux:tooltip :content="__('Notifications')" position="bottom">
                                <button class="nav-item-glass h-10 w-10 rounded-xl text-slate-700 dark:text-white flex items-center justify-center relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                    </svg>


                                </button>
                            </flux:tooltip>
                        </flux:modal.trigger>

                        <flux:modal name="notifications-user-desktop" variant="flyout" position="right" :closable="false" class="md:w-96 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700">
                            <livewire:admin.notifications :dashboardUser="false" />
                        </flux:modal>


                        <!-- User Profile Dropdown -->
                        <flux:dropdown position="bottom" align="end">
                            <flux:profile
                                class="cursor-pointer nav-item-glass rounded-xl"
                                :initials="auth()->user()->initials()"
                                avatar-class="bg-gradient-to-br from-[#364872] to-slate-600" />

                            <flux:menu class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl shadow-lg">
                                <!-- User Info Section -->
                                <flux:menu.radio.group>
                                    <div class="p-0 text-sm font-normal border-b border-slate-300 dark:border-white border-opacity-10">
                                        <div class="flex items-center gap-2 px-4 py-3 text-left text-sm">
                                            <span class="relative flex h-10 w-10 shrink-0 overflow-hidden rounded-lg">
                                                <span class="flex h-full w-full items-center justify-center rounded-lg bg-gradient-to-br from-[#364872] to-slate-600 text-white font-semibold">
                                                    {{ auth()->user()->initials() }}
                                                </span>
                                            </span>

                                            <div class="grid flex-1 text-left text-sm leading-tight">
                                                <span class="truncate font-semibold text-slate-700 dark:text-white">{{ auth()->user()->name }}</span>
                                                <span class="truncate text-xs text-slate-600 dark:text-white opacity-70">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </flux:menu.radio.group>

                                <flux:menu.separator class="border-slate-300 dark:border-white border-opacity-10" />

                                <!-- Settings -->
                                <flux:menu.radio.group>
                                    <flux:menu.item
                                        :href="route('settings.profile')"
                                        icon="cog"
                                        wire:navigate
                                        class="text-slate-700 dark:text-white hover:bg-white hover:bg-opacity-10">
                                        {{ __('Settings') }}
                                    </flux:menu.item>
                                </flux:menu.radio.group>

                                <flux:menu.separator class="border-slate-300 dark:border-white border-opacity-10" />

                                <!-- Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <flux:menu.item
                                        as="button"
                                        type="submit"
                                        icon="arrow-right-start-on-rectangle"
                                        class="w-full text-slate-700 dark:text-white hover:bg-white hover:bg-opacity-10">
                                        {{ __('Log Out') }}
                                    </flux:menu.item>
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <flux:sidebar stashable sticky
            class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')">
                    <flux:navlist.item icon="layout-grid" :href="route('dashboard')"
                        :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:navlist.item>

                    <flux:modal.trigger name="notifications-user">
                        <flux:navlist.item :icon="$icon">
                            {{ __('Notifikasi') }}
                        </flux:navlist.item>
                    </flux:modal.trigger>

                    <flux:navlist.item
                        icon="document-duplicate"
                        :href="route('pr.form')"
                        :current="request()->routeIs('pr.form')"
                        wire:navigate>
                        {{ __('Layanan Kehumasan') }}
                    </flux:navlist.item>



                    <flux:navlist.item
                        icon="clock"
                        :href="route('list.request')"
                        :current="request()->routeIs('list.request') "
                        wire:navigate>
                        {{ __('Histori Permohonan') }}
                    </flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            </div>

            <flux:spacer />
        </flux:sidebar>

        <x-user.dashboard.warning-modal-sop />

        {{ $slot }}

        @livewireScripts
        @fluxScripts
        <script>
            document.addEventListener('livewire:init', () => {
                Alpine.data('dashboard', () => ({
                    openDropdown: false,
                    openModal: false,
                    hasReadSOP: false,
                    sopConfirmed: false,

                    init() {
                        this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
                    },

                    handleSIDataRequest() {
                        this.hasReadSOP ?
                            Livewire.navigate('{{ route('
                                si - data.form ') }}') :
                            this.openModal = true;
                    },

                    handlePRRequest() {
                        Livewire.navigate('{{ route('
                            pr.form ') }}');
                    },

                    confirmReadSOP() {
                        sessionStorage.setItem('read_sop', 'true');
                        this.hasReadSOP = true;
                        Livewire.navigate('{{ route('
                            si - data.form ') }}');
                    },

                    closeModal() {
                        this.openModal = false;
                        this.sopConfirmed = false;
                    },
                }));
            });
        </script>
</body>

</html>