<?php

namespace Newnet\Seo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternalAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->attributes->get('_internal_forward')) {
            abort(404);
        }

        return $next($request);
    }
}
