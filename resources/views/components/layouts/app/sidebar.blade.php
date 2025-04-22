<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Main')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    @hasrole('user|administrator')
                    <flux:navlist.item icon="folder" :href="route('letter')" :current="request()->routeIs('letter')" wire:navigate>{{ __('Letter') }}</flux:navlist.item>
                    @endhasrole
                </flux:navlist.group>
            </flux:navlist>

            <flux:modal.trigger name="edit-profile">
                <flux:navlist variant="outline">
                    <flux:navlist.group class="grid">
                        <flux:navlist.item icon="bell-alert" badge="100" badge-color="lime">{{ __('Notifications') }}</flux:navlist.item>
                    </flux:navlist.group>
                </flux:navlist>
             
            </flux:modal.trigger>

            <flux:modal name="edit-profile" variant="flyout" position="right">
                <div class="space-y-6">
                    <div>
                        <flux:heading size="lg">Notification</flux:heading>
                        <flux:text class="mt-2">Test notifications</flux:text>
                    </div>
                </div>
            </flux:modal>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Manage')" class="grid">
                    <flux:navlist.group expandable heading="Tabel" class=" lg:grid">
                        <flux:navlist.item :href="route('letter.table')" wire:navigate>Application</flux:navlist.item>
                        <flux:navlist.item href="#">Data</flux:navlist.item>
                        <flux:navlist.item href="#">Humas</flux:navlist.item>
                    </flux:navlist.group>
                    @hasrole('administrator')
                    <flux:navlist.group expandable heading="System" class=" lg:grid">
                        <flux:navlist.item :href="route('admin.users')" wire:navigate>User</flux:navlist.item>
                        <flux:navlist.item wire:navigate>Template</flux:navlist.item>
                    </flux:navlist.group>
                    @endhasrole
                </flux:navlist.group>
            </flux:navlist>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Monitoring')" class="grid">
                    <flux:navlist.item icon="newspaper" :href="route('letter')" :current="request()->routeIs('letter')" wire:navigate>{{ __('Reporting') }}</flux:navlist.item>
                    <flux:navlist.item icon="cake" :href="route('letter')" :current="request()->routeIs('letter')" wire:navigate>{{ __('Events & Logs') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer/>

            <!-- Desktop User Menu -->
            <flux:dropdown position="top" align="start" class="hidden lg:block">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
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
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
    </body>
</html>
