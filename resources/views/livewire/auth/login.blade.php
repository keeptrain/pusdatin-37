<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-neutral-900 px-4 py-8">
    <div
        class="flex flex-col md:flex-row bg-white dark:bg-stone-950 shadow-xl rounded-2xl overflow-hidden w-full max-w-3xl">

        <!-- Kolom Gambar Kiri -->
        <div class="hidden md:block md:w-1/2 bg-green-100 dark:bg-green-900">
            <img src="{{ asset('images/dinkes.jpg') }}" alt="Dinas Kesehatan"
                class="h-full w-full object-cover object-center brightness-75" />
        </div>

        <!-- Kolom Form Login Kanan -->
        <div
            class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center gap-5 relative bg-white dark:bg-stone-950 text-stone-800 dark:text-white">

            <!-- Dekorasi blur -->
            <div
                class="absolute -top-10 -left-10 w-32 h-16 bg-gradient-to-br from-green-300 to-green-500 rounded-full opacity-20 blur-3xl z-0">
            </div>

            <div class="relative flex flex-col gap-5">
                <a class="flex flex-col items-center gap-2 font-medium">
                    <span class="flex h-10 w-10 items-center justify-center rounded-full">
                        <x-lucide-log-in class="w-8 h-8" />
                    </span>
                </a>

                <x-auth-header :title="__('Masuk ke akun Anda')" :description="null" />

                <!-- Session Status -->
                <x-auth-session-status class="text-center text-sm text-green-600 dark:text-green-400"
                    :status="session('status')" />

                {{-- <form wire:submit="$dispatchTo('auth.login', 'executeCaptchaValidation')" x-data="{
                    siteKey: @js(config('services.recaptcha.site_key')),
                    init() {
                        if (!window.recaptcha) {
                            const script = document.createElement('script');
                            script.src = 'https://www.google.com/recaptcha/api.js?render=' + this.siteKey;
                            script.async = true;
                            script.defer = true;
                            document.body.append(script);
                        }
                    }
                }" 
                class="flex flex-col gap-6"> --}}
                <form wire:submit="login" class="flex flex-col gap-6">
                    <flux:input wire:model="email" :label="__('Email address')" type="email" required autofocus
                        autocomplete="email" placeholder="email@example.com" />

                    <div class="relative">
                        <flux:input wire:model="password" :label="__('Password')" type="password" required
                            autocomplete="current-password" :placeholder="__('Password')" />
                        @if (Route::has('password.request'))
                            <flux:link variant="subtle"
                                class="absolute right-0 top-0 mt-1 text-sm text-green-600 dark:text-green-400"
                                :href="route('password.request')" wire:navigate>
                                {{ __('Lupa password?') }}
                            </flux:link>
                        @endif
                    </div>

                    @error('recaptcha')
                        <flux:error :message="$message" />
                    @enderror

                    <!-- Remember Me -->
                    <flux:checkbox wire:model="remember" :label="__('Remember me')" />

                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Log in') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- @script
<script>
    $wire.on('executeCaptchaValidation', () => {
        grecaptcha.ready(() => {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {
                action: 'login'
            }).then((token) => {
                $wire.dispatch('captchaResponse', { token: token });
            }).catch(error => {
                console.error('reCAPTCHA Error:', error);
            });
        });
    });
</script>
@endscript --}}