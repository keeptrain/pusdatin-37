<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" />
    @endif
    <flux:sidebar sticky stashable container
        class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
            <x-app-logo />
        </a>

        @unlessrole('administrator')
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Main')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>

                <flux:navlist variant="outline">
                    <flux:modal.trigger name="notifications-admin">
                        <flux:navlist.item icon="bell">
                            <div class="flex items-center">
                                <span>{{ __('Notifikasi') }}</span>
                                <div x-show="$store.notifications.hasUnread"
                                    class="ml-4 w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse" wire:cloak></div>
                            </div>
                        </flux:navlist.item>
                    </flux:modal.trigger>
                </flux:navlist>

                <flux:modal name="notifications-admin" variant="flyout" position="right" :closable="false"
                    class="md:w-96">
                    <livewire:admin.notifications />
                </flux:modal>

            </flux:navlist.group>
        </flux:navlist>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Manajemen')" class="grid">
                @hasanyrole('head_verifier')
                <flux:navlist.item :href="route('is.index')" icon="folder-open"
                    :current="request()->routeIs('is.index')" wire:navigate>Layanan SI & Data</flux:navlist.item>
                @endrole
                @hasanyrole('si_verifier')
                <flux:navlist.item :href="route('is.index')" icon="folder-open"
                    :current="request()->routeIs('is.index')" wire:navigate>Layanan SI</flux:navlist.item>
                @endrole
                @hasanyrole('data_verifier')
                <flux:navlist.item :href="route('is.index')" icon="folder-open"
                    :current="request()->routeIs('is.index')" wire:navigate>Layanan Data</flux:navlist.item>
                @endrole
                @hasanyrole('head_verifier|pr_verifier|promkes_verifier')
                <flux:navlist.item :href="route('pr.index')" icon="folder-open"
                    :current="request()->routeIs('pr.index')" wire:navigate>Layanan Kehumasan</flux:navlist.item>
                @endrole
                <flux:navlist.item :href="route('discussions')" icon="chat-bubble-left-right"
                    :current="request()->routeIs('discussions')" wire:navigate>Forum Diskusi</flux:navlist.item>
                @unlessrole('head_verifier|promkes_verifier')
                <flux:navlist.item :href="route('show.ratings')" icon="star"
                    :current="request()->routeIs('show.ratings')" wire:navigate>Penilaian Layanan</flux:navlist.item>
                @endunlessrole
            </flux:navlist.group>
        </flux:navlist>
        @endunlessrole

        @unlessrole('administrator|promkes_verifier')
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Sistem')" class="grid">
                @hasanyrole('si_verifier|data_verifier|pr_verifier|head_verifier')
                <flux:navlist.item :href="route('analytic.index')" icon="chart-pie" wire:navigate>
                    Analitik
                </flux:navlist.item>
                @endhasanyrole

                @hasanyrole('si_verifier|data_verifier|pr_verifier')
                <flux:navlist.item :href="route('manage.templates')" icon="document-text" wire:navigate>
                    Templates
                </flux:navlist.item>
                @endhasanyrole
            </flux:navlist.group>
        </flux:navlist>
        @endunlessrole

        @hasrole('administrator')
        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Main')" class="grid">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            </flux:navlist.group>
            <flux:navlist.group :heading="__('Systems')" class="grid">
                <flux:navlist.item :href="route('manage.users')" icon="users"
                    :current="request()->routeIs('manage.users')" wire:navigate>
                    Users
                </flux:navlist.item>
                {{-- <flux:navlist.item :href="route('manage.users')" icon="building-office" wire:navigate>
                    Permissions
                </flux:navlist.item>
                <flux:navlist.item :href="route('manage.users')" icon="building-office" wire:navigate>
                    Seksi
                </flux:navlist.item> --}}
            </flux:navlist.group>
        </flux:navlist>
        @endhasrole

        <flux:spacer />

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="start" class="hidden lg:block">
            <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                icon-trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">
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
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

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

    {{ $slot }}

    @fluxScripts
    @stack('scripts')

</body>

</html>