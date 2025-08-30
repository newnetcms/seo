<?php

namespace Newnet\Seo\Http\Controllers\Web;

use App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Newnet\Seo\Models\ErrorRedirect;
use Newnet\Seo\Models\Url;
use Newnet\Seo\Repositories\UrlRepositoryInterface;

class UrlRewriteController extends Controller
{
    /**
     * @var UrlRepositoryInterface
     */
    protected $urlRepository;

    const IS_HOMEPAGE = '_IS_HOMEPAGE_';

    const IS_NOT_FOUND = '_IS_NOT_FOUND_';

    public function __construct(UrlRepositoryInterface $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    public function __invoke(Request $request, $path = '')
    {
        $path = ltrim(rtrim($path, '/'), '/') ?: '/';

        $targetPath = $this->getTargetPath($path);
        if ($targetPath == self::IS_HOMEPAGE) {
            return $this->renderHomePage();
        }

        if ($targetPath == self::IS_NOT_FOUND) {
            return $this->redirectError($request);
        }

        return $this->handleRealRoute($targetPath, $request);
    }

    protected function handleRealRoute($targetPath, $request)
    {
        $forwardRequest = Request::create(
            $targetPath,
            $request->method(),
            $request->all(),
            $request->cookies->all(),
            $request->allFiles(),
            $request->server->all(),
            $request->getContent()
        );

        $forwardRequest->headers->replace($request->headers->all());
        $forwardRequest->attributes->set('_original_url', $request->fullUrl());
        $forwardRequest->attributes->set('_internal_forward', true);

        return app()->handle($forwardRequest);
    }

    protected function renderHomePage()
    {
        if (view()->exists('index')) {
            return view('seo::web.index');
        }

        return null;
    }

    protected function getTargetPath($path)
    {
        $matchRequestPath = $this->urlRepository->whereMathRequestPath($path);

        /** @var Url $urlRewrite */
        $urlRewrite = $matchRequestPath->where('locale', App::getLocale())->first();

        if (!$urlRewrite) {
            $urlRewrite = $matchRequestPath->first();
        }

        if (!$urlRewrite && $path == '/') {
            return self::IS_HOMEPAGE;
        }

        if (!$urlRewrite) {
            return self::IS_NOT_FOUND;
        }

        return $urlRewrite->target_path;
    }

    protected function redirectError(Request $request)
    {
        $currentUrl = $request->url();
        $currentPath = $request->path();

        // Cache redirects để tăng performance
        $redirects = Cache::remember('active_error_redirects', 3600, function () {
            return ErrorRedirect::get();
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

        throw new ModelNotFoundException();
    }
}
