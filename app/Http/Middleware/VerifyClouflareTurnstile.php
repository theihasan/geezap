<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class VerifyClouflareTurnstile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $request->input('cf-turnstile-response');
        $secretKey = config('services.cloudflare.turnstile.secret_key');

        if (!$response || !$this->verifyTurnstile($secretKey, $response)) {
            return back()->withErrors([
                'turnstile' => 'Turnstile verification failed',
            ])->withInput();
        }

        return $next($request);
    }

    private function verifyTurnstile(string $secretKey, string $response): bool
    {
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secretKey,
            'response' => $response,
        ]);

        return $response->json('success', false);
    }
}
