<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function __construct(public string $action)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        $body = $response->json();

        if (
            !isset($body['success']) || $body['success'] !== true ||
            !isset($body['score']) || $body['score'] < config('services.recaptcha.score_threshold') ||
            !isset($body['action']) || $body['action'] !== $this->action
        ) {
            $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
        }
    }
}
