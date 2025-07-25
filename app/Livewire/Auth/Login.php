<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Rules\Recaptcha;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Login extends Component
{
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    // #[Validate(['required', new Recaptcha('login')])]
    // public ?string $recaptcha = null;

    /**
     * Handle an incoming authentication request.
     */
    // #[On('captchaResponse')]
    public function login(): void
    {
        // $this->recaptcha = $token;
        $this->validate();

        $this->ensureIsNotRateLimited();

        // Single query to get user with email
        $user = User::where('email', $this->email)->first();

        // If user doesn't exist or password is wrong
        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages(
                $user
                ? ['password' => __('auth.password')]
                : ['email' => __('auth.failed')]
            );
        }

        // Log in the user
        Auth::login($user, $this->remember);

        // Clear rate limiter
        RateLimiter::clear($this->throttleKey());

        // Regenerate session
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
