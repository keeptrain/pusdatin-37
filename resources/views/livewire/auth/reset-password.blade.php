<div class="flex flex-col min-h-svh items-center justify-center gap-6 p-6 md:p-10">
    <!-- Header -->
    <x-auth-header :title="__('Reset password')" :description="__('Silakan masukkan kata sandi baru Anda di bawah ini')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Form -->
    <form wire:submit="resetPassword" class="w-full max-w-sm space-y-6">
        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
            class="w-full"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
            class="w-full"
            viewable
        />

        <!-- Submit Button -->
        <flux:button
            type="submit"
            variant="primary"
            class="w-full"
        >
            {{ __('Reset password') }}
        </flux:button>
    </form>
</div>