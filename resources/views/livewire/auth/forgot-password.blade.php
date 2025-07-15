<div class="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
    <div class="flex w-full max-w-md flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="rounded-xl bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                <div class="px-10 py-8">
                    <div class="flex flex-col gap-6">
                        <x-auth-header :title="__('Forgot password')" :description="__('Masukkan email Anda untuk menerima link pengaturan ulang kata sandi')" />

                        <!-- Session Status -->
                        <x-auth-session-status class="text-center" :status="session('status')" />

                        <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
                            <!-- Email Address -->
                            <flux:input wire:model="email" :label="__('Email Address')" type="email" required autofocus
                                placeholder="email@example.com" />

                            <flux:button variant="primary" type="submit" class="w-full">
                                {{ __('Email password reset link') }}
                            </flux:button>
                        </form>

                        <div class="space-x-1 text-center text-sm text-zinc-400">
                            {{ __('Atau, kembali ke') }}
                            <flux:link :href="route('login')" wire:navigate>{{ __('log in') }}</flux:link>
                        </div>
                    </div>

                </div>
                <div
                    class="absolute -top-10 -left-10 w-100 h-20 bg-gradient-to-br from-indigo-300 to-indigo-500 rounded-full opacity-20 blur-3xl">
                </div>
            </div>
        </div>
    </div>
</div>