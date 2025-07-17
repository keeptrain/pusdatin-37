<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @livewireStyles
    @include('partials.head')
</head>

<body x-data="header" class="min-h-screen bg-white dark:bg-zinc-800 p-0">
    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" />
    @endif

    <flux:header sticky container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('dashboard') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navbar class="-mb-px max-lg:hidden">
            <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                {{ __('Dashboard') }}
            </flux:navbar.item>
            <div class="container mx-auto">
                <ul>
                    <li
                        x-on:mouseenter="openDropdown = true; clearTimeout(timeOutDropdown)"
                        x-on:mouseleave="closeDropdown()">
                        <flux:navbar.item icon="folder-open" icon:trailing="chevron-down"
                            :current="request()->routeIs('si-data.form') || request()->routeIs('pr.form')">
                            {{ __('Ajukan Permohonan') }}
                        </flux:navbar.item>
                    
                        <x-menu.dropdown-menu-on-dashboard-user />
                    </li>
                </ul>
            </div>
            <flux:navbar.item icon="book-open-text" :href="route('list.request')"
                :current="request()->routeIs('list.request') || request()->routeIs('detail.request')" wire:navigate>
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

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

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

                <flux:navlist.item icon="folder" :href="route('list.request')"
                    :current="request()->routeIs('list.request') || request()->routeIs('detail.request')" wire:navigate>
                    {{ __('Permohonan') }}
                </flux:navlist.item>

            </flux:navlist.group>
        </flux:navlist>x

        <flux:spacer />

    </flux:sidebar>

    {{ $slot }}

    @livewireScripts
    @fluxScripts
    <script>
        Alpine.data('header', () => ({
            openDropdown: false,
            timeOutDropdown: null,
            openModal: false,
            hasReadSOP: false,
            sopConfirmed: false,

            init() {
                this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
            },

            handleSIDataRequest() {
                this.hasReadSOP ?
                    Livewire.navigate('{{ route('si-data.form') }}') :
                    this.openModal = true;
            },

            handlePRRequest() {
                Livewire.navigate('{{ route('pr.form') }}');
            },

            confirmReadSOP() {
                sessionStorage.setItem('read_sop', 'true');
                this.hasReadSOP = true;
                Livewire.navigate('{{ route('si-data.form') }}');
            },

            closeModal() {
                this.openModal = false;
                this.sopConfirmed = false;
            },

            closeDropdown() {
                this.timeOutDropdown = setTimeout(() => {
                    this.openDropdown = false;
                }, 200);
            },
        }));
    </script>

    <x-user.dashboard.warning-modal-sop />
    
</body>

</html>