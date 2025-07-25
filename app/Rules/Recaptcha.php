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
        try {
            $response = Http::timeout(5)
                ->asForm()
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip()
                ]);

            // Check if the HTTP request to reCAPTCHA API failed
            if ($response->failed()) {
                throw new \Exception('Failed to verify reCAPTCHA');
            }

            $body = $response->json();

            // Check if reCAPTCHA verification failed
            if (!isset($body['success']) || $body['success'] !== true) {
                $errorCode = $body['error-codes'][0] ?? 'unknown';
                throw new \Exception("reCAPTCHA verification failed: {$errorCode}");
            }

            // Verify the score meets the threshold (0.0 - 1.0)
            // Lower score means more likely to be a bot
            if (!isset($body['score']) || $body['score'] < config('services.recaptcha.score_threshold', 0.5)) {
                $fail('Aktivitas mencurigakan terdeteksi. Silakan coba lagi nanti.');
                return;
            }

            // Verify the action matches what we expect (e.g., 'login', 'register')
            if (!isset($body['action']) || $body['action'] !== $this->action) {
                throw new \Exception('reCAPTCHA action mismatch');
            }

        } catch (\Exception $e) {
            $fail('Verifikasi keamanan gagal. Silakan coba kembali.');
        }
    }
}
