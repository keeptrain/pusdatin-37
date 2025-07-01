<div class="flex flex-col gap-6">
    <a class="flex flex-col items-center gap-2 font-medium">
        <span class="flex h-9 w-9 items-center justify-center rounded-md">
            <x-lucide-log-in />
        </span>
    </a>
    <x-auth-header :title="__('Masuk ke akun Anda')" :description="null" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    {{-- <form @submit.prevent="doCaptcha" x-data="{
        siteKey: @js(config('services.recaptcha.site_key')),
        init() {
            if (!window.recaptcha) {
                const script = document.createElement('script');
                script.src = 'https://www.google.com/recaptcha/api.js?render=' + this.siteKey;
                script.async;
                script.defer;
                document.body.append(script);
            }
        },
        doCaptcha() {
            grecaptcha.ready(() => {
                grecaptcha.execute(this.siteKey, { action: 'login' }).then(token => {
                    Livewire.dispatch('formSubmitted', { token: token });
                });
            });
        },
    }" class="flex flex-col gap-6"> --}}
    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input wire:model="email" :label="__('Email address')" type="email" required autofocus autocomplete="email"
            placeholder="email@example.com" />

        <!-- Password -->
        <div class="relative">
            <flux:input wire:model="password" :label="__('Password')" type="password" required
                autocomplete="current-password" :placeholder="__('Password')" />

            @if (Route::has('password.request'))
                <flux:link variant="subtle" class="absolute right-0 top-0 text-sm" :href="route('password.request')"
                    wire:navigate>
                    {{ __('Lupa password?') }}
                </flux:link>
            @endif
        </div>

        {{-- @error('recaptcha')
            <div class="bg-red-300 text-red-700 p-3 rounded">{{ $message }}</div>
        @enderror --}}

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Log in') }}
            </flux:button>
        </div>
    </form>
</div>