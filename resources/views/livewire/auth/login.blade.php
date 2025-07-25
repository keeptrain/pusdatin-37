<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-neutral-900 px-4 py-8">
    <div
        class="flex flex-col md:flex-row bg-white dark:bg-stone-950 shadow-xl rounded-2xl overflow-hidden w-full max-w-3xl">
        <!-- Kolom Gambar Kiri - Fixed height container -->
        <div class="hidden md:block md:w-1/2 h-[500px]">
            <img src="{{ asset('images/dinkes.jpg') }}" alt="Dinas Kesehatan"
                class="h-full w-full object-cover object-center brightness-65" />
        </div>

        <!-- Kolom Form Kanan - Fixed height container with scroll -->
        <div
            class="w-full md:w-1/2 p-6 md:p-8 flex flex-col relative bg-white dark:bg-stone-950 text-stone-800 dark:text-white h-[500px] overflow-y-auto">
            <div class="flex-1 flex flex-col gap-5 min-h-0">
                <div class="shrink-0 flex justify-center">
                    <x-lucide-log-in class="w-8 h-8 mt-2" />
                </div>

                <div class="shrink-0">
                    <x-auth-header :title="__('Masuk ke akun Anda')" :description="null" />
                </div>

                <!-- Session Status -->
                <div class="shrink-0">
                    <x-auth-session-status class="text-center text-sm text-green-600 dark:text-green-400"
                        :status="session('status')" />
                </div>

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
                    }" class="flex-1 flex flex-col gap-4 min-h-0"> --}}
                <form wire:submit="login" class="flex-1 flex flex-col gap-4 min-h-0">
                    <div class="space-y-4">
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

                        {{-- @error('recaptcha')
                            <flux:error :message="$message" />
                        @enderror --}}

                        <!-- Remember Me -->
                        <flux:checkbox wire:model="remember" :label="__('Remember me')" />
                    </div>

                    <div class="mt-auto">
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