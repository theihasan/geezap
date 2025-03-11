<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class BlockCrawlerMiddleware
{
    /**
     * List of known bot User-Agents
     *
     * @var array
     */
    protected $botAgents = [
        'bot', 'crawl', 'slurp', 'spider', 'mediapartners', 'wget', 'curl', 'python-requests',
        'scrapy', 'httpclient', 'aiohttp', 'java', 'urllib', 'Go-http-client', 'facebook',
        'headlesschrome', 'phantomjs', 'selenium', 'puppeteer', 'baiduspider', 'yandexbot',
        'ahrefsbot', 'mj12bot', 'semrushbot', 'bingbot', 'duckduckbot'
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Skip check if no User-Agent header present
        if (!$request->hasHeader('User-Agent')) {
            return $this->blockAccess($request);
        }

        $userAgent = strtolower($request->header('User-Agent'));

        // Empty User-Agent is suspicious
        if (empty($userAgent)) {
            return $this->blockAccess($request);
        }

        // Check for bot signatures
        foreach ($this->botAgents as $bot) {
            if (strpos($userAgent, $bot) !== false) {
                return $this->blockAccess($request);
            }
        }

        return $next($request);
    }

    /**
     * Block access with a 403 response
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function blockAccess(Request $request): Response
    {
        // Log URL and IP
        Log::info("Blocked crawler from accessing {$request->fullUrl()} from {$request->ip()}");

        // Return a 403 response
        abort(403, 'Access denied: Crawler detected.');
    }
}
