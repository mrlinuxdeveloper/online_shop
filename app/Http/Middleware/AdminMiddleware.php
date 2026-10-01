<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (
            !auth()->user()->adminUser ||
            auth()->user()->adminUser->role !== 'admin'
        ) {
            abort(403);
        }

        return $next($request);
    }
}