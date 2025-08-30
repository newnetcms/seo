<?php

namespace Newnet\Seo\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Newnet\Seo\Models\PreRedirect;

class PreRedirectMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('cms.seo.enable_pre_redirect')) {
            return $next($request);
        }

        if ($request->is(config('core.admin_prefix').'*')) {
            return $next($request);
        }

        $currentUrl = $request->url();
        $currentPath = $request->path();

        // Cache redirects để tăng performance
        $redirects = Cache::remember('active_pre_redirects', 3600, function () {
            return PreRedirect::get();
        });

        foreach ($redirects as $redirect) {
            // So sánh exact URL
            if (rtrim($currentUrl, '/') === rtrim($redirect->from_path, '/')) {
                return redirect($redirect->to_url, $redirect->status_code);
            }

            // So sánh path (không bao gồm domain)
            if ('/' . $currentPath === $redirect->from_path || $currentPath === trim($redirect->from_path, '/')) {
                return redirect($redirect->to_url, $redirect->status_code);
            }
        }

        return $next($request);
    }
}
