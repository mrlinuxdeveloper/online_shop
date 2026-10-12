<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LogWebActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);

        try {
            $response = $next($request);
        } catch (Throwable $exception) {
            $this->write($request, 500, $startedAt, [
                'exception' => $exception::class,
            ]);

            throw $exception;
        }

        $this->write($request, $response->getStatusCode(), $startedAt);

        return $response;
    }

    private function write(Request $request, int $status, float $startedAt, array $extra = []): void
    {
        $route = $request->route();
        $action = is_object($route) ? $route->getActionName() : null;
        $routeName = is_object($route) ? $route->getName() : null;
        $context = array_merge([
            'route' => $routeName ?: $request->path(),
            'action' => $action,
            'method' => $request->method(),
            'status' => $status,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            'user_id' => $request->user()?->getAuthIdentifier(),
        ], $extra);

        $level = $status >= 500 ? 'error' : ($status >= 400 ? 'warning' : 'info');

        Log::channel('activity')->log($level, 'Web request completed.', $context);
    }
}
