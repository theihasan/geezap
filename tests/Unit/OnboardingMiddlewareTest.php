<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsureOnboardingCompleted;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OnboardingMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private function createMiddleware(): EnsureOnboardingCompleted
    {
        return new EnsureOnboardingCompleted();
    }

    private function createRequest(string $uri = '/dashboard'): Request
    {
        return Request::create($uri, 'GET');
    }

    private function createIncompleteUser(): User
    {
        return User::factory()->create([
            'onboarding_completed_at' => null,
        ]);
    }

    private function createCompleteUser(): User
    {
        return User::factory()->create([
            'onboarding_completed_at' => now(),
        ]);
    }

    public function test_allows_completed_users_to_proceed(): void
    {
        $user = $this->createCompleteUser();
        $request = $this->createRequest();
        $request->setUserResolver(fn() => $user);

        $middleware = $this->createMiddleware();
        $response = null;

        $next = function ($req) use (&$response) {
            $response = new Response('Success');
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertSame($response, $result);
        $this->assertEquals('Success', $result->getContent());
    }

    public function test_redirects_incomplete_users_to_onboarding_welcome(): void
    {
        $user = $this->createIncompleteUser();
        $request = $this->createRequest('/dashboard');
        $request->setUserResolver(fn() => $user);

        $middleware = $this->createMiddleware();

        $next = function ($req) {
            return new Response('Should not reach here');
        };

        $result = $middleware->handle($request, $next);

        $this->assertEquals(302, $result->getStatusCode());
        $this->assertEquals(route('onboarding.welcome'), $result->getTargetUrl());
    }

    public function test_allows_access_to_onboarding_routes(): void
    {
        $user = $this->createIncompleteUser();

        $onboardingRoutes = [
            '/onboarding/welcome',
            '/onboarding/essential-info',
            '/onboarding/preferences',
            '/onboarding/skip',
        ];

        $middleware = $this->createMiddleware();

        foreach ($onboardingRoutes as $route) {
            $request = $this->createRequest($route);
            $request->setUserResolver(fn() => $user);

            $response = null;
            $next = function ($req) use (&$response) {
                $response = new Response('Allowed');
                return $response;
            };

            $result = $middleware->handle($request, $next);

            $this->assertSame($response, $result);
            $this->assertEquals('Allowed', $result->getContent());
        }
    }

    public function test_allows_access_to_auth_routes(): void
    {
        $user = $this->createIncompleteUser();

        $authRoutes = [
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/verify-email',
        ];

        $middleware = $this->createMiddleware();

        foreach ($authRoutes as $route) {
            $request = $this->createRequest($route);
            $request->setUserResolver(fn() => $user);

            $response = null;
            $next = function ($req) use (&$response) {
                $response = new Response('Auth route allowed');
                return $response;
            };

            $result = $middleware->handle($request, $next);

            $this->assertSame($response, $result);
            $this->assertEquals('Auth route allowed', $result->getContent());
        }
    }

    public function test_allows_access_to_api_routes(): void
    {
        $user = $this->createIncompleteUser();
        $request = $this->createRequest('/api/jobs');
        $request->setUserResolver(fn() => $user);

        $middleware = $this->createMiddleware();

        $response = null;
        $next = function ($req) use (&$response) {
            $response = new Response('API route allowed');
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertSame($response, $result);
        $this->assertEquals('API route allowed', $result->getContent());
    }

    public function test_handles_unauthenticated_users(): void
    {
        $request = $this->createRequest('/dashboard');
        $request->setUserResolver(fn() => null);

        $middleware = $this->createMiddleware();

        $response = null;
        $next = function ($req) use (&$response) {
            $response = new Response('Unauthenticated user allowed');
            return $response;
        };

        $result = $middleware->handle($request, $next);

        $this->assertSame($response, $result);
        $this->assertEquals('Unauthenticated user allowed', $result->getContent());
    }

    public function test_handles_users_without_onboarding_completed_at_field(): void
    {
        // Create user without the onboarding_completed_at field set
        $user = User::factory()->create();
        $user->onboarding_completed_at = null;
        $user->save();

        $request = $this->createRequest('/dashboard');
        $request->setUserResolver(fn() => $user);

        $middleware = $this->createMiddleware();

        $next = function ($req) {
            return new Response('Should not reach here');
        };

        $result = $middleware->handle($request, $next);

        $this->assertEquals(302, $result->getStatusCode());
        $this->assertEquals(route('onboarding.welcome'), $result->getTargetUrl());
    }
}
