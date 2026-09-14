<?php

namespace App\Http\Middleware;

use App\Models\EMCustomer;
use App\Models\EMCustomerRememberToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class EMCustomerRemember
{
    private const COOKIE_NAME = 'aces_training_remember';
    private const REMEMBER_DAYS = 30;

    public function handle(Request $request, Closure $next)
    {
        if (!$request->is('training*')) {
            return $next($request);
        }

        if (!Schema::hasTable('em_customer_remember_tokens')) {
            return $next($request);
        }

        if (!session('em_customer_id')) {
            $this->restoreRememberedCustomer($request);
        }

        $routeName = optional($request->route())->getName();
        $presentedCookie = $request->cookie(self::COOKIE_NAME);

        if ($routeName === 'em.customer.logout') {
            $this->revokePresentedToken($presentedCookie);
        }

        $response = $next($request);

        if (in_array($routeName, [
            'em.customer.login.submit',
            'em.customer.password.store',
            'em.customer.register.submit',
        ], true) && session('em_customer_id')) {
            $this->revokePresentedToken($presentedCookie);
            $this->issueRememberToken($request, (int) session('em_customer_id'));
        }

        if ($routeName === 'em.customer.logout') {
            $this->forgetCookie();
        }

        return $response;
    }

    private function restoreRememberedCustomer(Request $request): void
    {
        $cookie = (string) $request->cookie(self::COOKIE_NAME, '');
        if ($cookie === '') {
            return;
        }

        $this->pruneExpiredTokens();
        [$selector, $validator] = $this->parseCookie($cookie);

        if (!$selector || !$validator) {
            $this->forgetCookie();
            return;
        }

        $token = EMCustomerRememberToken::where('selector', $selector)->first();
        if (!$token || !$token->expires_at || $token->expires_at->lte(now())) {
            $token?->delete();
            $this->forgetCookie();
            return;
        }

        if (!hash_equals((string) $token->validator_hash, hash('sha256', $validator))) {
            $this->forgetCookie();
            return;
        }

        $customer = EMCustomer::query()
            ->whereKey($token->customer_id)
            ->where('active', 1)
            ->first();

        if (!$customer) {
            $token->delete();
            $this->forgetCookie();
            return;
        }

        $request->session()->regenerate();
        $request->session()->put('em_customer_id', $customer->id);
        $request->session()->put('em_customer_name', $customer->display_name);
        $request->session()->put(
            'em_customer_account_type',
            $customer->account_type ?: EMCustomer::ACCOUNT_TYPE_USER
        );

        $newValidator = bin2hex(random_bytes(32));
        $token->validator_hash = hash('sha256', $newValidator);
        $token->last_used_at = now();
        $token->user_agent = Str::limit((string) $request->userAgent(), 500, '');
        $token->ip_address = Str::limit((string) $request->ip(), 45, '');
        $token->save();

        $minutesRemaining = max(1, (int) now()->diffInMinutes($token->expires_at, false));
        $this->queueCookie($token->selector . ':' . $newValidator, $minutesRemaining);
    }

    private function issueRememberToken(Request $request, int $customerId): void
    {
        $this->pruneExpiredTokens();

        $customer = EMCustomer::query()
            ->whereKey($customerId)
            ->where('active', 1)
            ->first();

        if (!$customer) {
            return;
        }

        $selector = bin2hex(random_bytes(16));
        $validator = bin2hex(random_bytes(32));

        EMCustomerRememberToken::create([
            'customer_id' => $customer->id,
            'selector' => $selector,
            'validator_hash' => hash('sha256', $validator),
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
            'ip_address' => Str::limit((string) $request->ip(), 45, ''),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(self::REMEMBER_DAYS),
        ]);

        $this->queueCookie($selector . ':' . $validator, self::REMEMBER_DAYS * 24 * 60);
    }

    private function revokePresentedToken(?string $cookie): void
    {
        if (!$cookie) {
            return;
        }

        [$selector] = $this->parseCookie($cookie);
        if ($selector) {
            EMCustomerRememberToken::where('selector', $selector)->delete();
        }
    }

    private function parseCookie(string $cookie): array
    {
        $parts = explode(':', $cookie, 2);
        if (count($parts) !== 2) {
            return [null, null];
        }

        [$selector, $validator] = $parts;
        if (
            strlen($selector) !== 32 || strlen($validator) !== 64 ||
            !ctype_xdigit($selector) || !ctype_xdigit($validator)
        ) {
            return [null, null];
        }

        return [$selector, $validator];
    }

    private function pruneExpiredTokens(): void
    {
        EMCustomerRememberToken::where('expires_at', '<=', now())->delete();
    }

    private function cookiePath(): string
    {
        return rtrim((string) request()->getBasePath(), '/') . '/training';
    }

    private function cookieIsSecure(): bool
    {
        return app()->environment('production') || request()->isSecure();
    }

    private function queueCookie(string $value, int $minutes): void
    {
        Cookie::queue(Cookie::make(
            self::COOKIE_NAME,
            $value,
            $minutes,
            $this->cookiePath(),
            null,
            $this->cookieIsSecure(),
            true,
            false,
            'lax'
        ));
    }

    private function forgetCookie(): void
    {
        Cookie::queue(Cookie::forget(self::COOKIE_NAME, $this->cookiePath()));
    }
}
