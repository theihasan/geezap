<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\BlockCrawlerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BlockCrawlerMiddlewareTest extends TestCase
{
    /**
     * Test that legitimate browsers can pass through
     */
    public function test_allows_legitimate_browsers()
    {
        $middleware = new BlockCrawlerMiddleware();
        $request = new Request();

        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        $response = $middleware->handle($request, function ($req) {
            return 'passed';
        });

        $this->assertEquals('passed', $response);
    }

    /**
     * Test that known bots are blocked
     */
    public function test_blocks_known_bots()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        Log::shouldReceive('info')
            ->once()
            ->with(
                $this->stringContains('Blocked crawler from accessing')
            );

        $middleware = new BlockCrawlerMiddleware();
        $request = new Request();

        $request->headers->set('User-Agent', 'Googlebot/2.1 (+http://www.google.com/bot.html)');

        $middleware->handle($request, function ($req) {
            return 'passed';
        });
    }

    /**
     * Test that empty User-Agent is blocked
     */
    public function test_blocks_empty_user_agent()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        Log::shouldReceive('info')->once();

        $middleware = new BlockCrawlerMiddleware();
        $request = new Request();

        $request->headers->set('User-Agent', '');

        $middleware->handle($request, function ($req) {
            return 'passed';
        });
    }

    /**
     * Test that missing User-Agent is blocked
     */
    public function test_blocks_missing_user_agent()
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        Log::shouldReceive('info')->once();

        $middleware = new BlockCrawlerMiddleware();
        $request = new Request();

        // Don't set User-Agent at all

        $middleware->handle($request, function ($req) {
            return 'passed';
        });
    }
}
