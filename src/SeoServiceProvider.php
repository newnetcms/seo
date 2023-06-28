<?php

namespace Newnet\Seo;

use Blade;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Newnet\Seo\Http\Middleware\PreRedirectMiddleware;
use Newnet\Seo\Models\ErrorRedirect;
use Newnet\Seo\Models\PreRedirect;
use Newnet\Seo\Repositories\Eloquent\ErrorRedirectRepository;
use Newnet\Seo\Repositories\Eloquent\PreRedirectRepository;
use Newnet\Seo\Repositories\ErrorRedirectRepositoryInterface;
use Newnet\Seo\Repositories\PreRedirectRepositoryInterface;
use Newnet\Seo\Http\Middleware\SeoFriendlyUrlRewriteMiddleware;
use Newnet\Seo\Models\Meta;
use Newnet\Seo\Models\Url;
use Newnet\Seo\Repositories\Eloquent\MetaRepository;
use Newnet\Seo\Repositories\Eloquent\UrlRepository;
use Newnet\Seo\Repositories\MetaRepositoryInterface;
use Newnet\Seo\Repositories\UrlRepositoryInterface;
use Newnet\Module\Support\BaseModuleServiceProvider;

class SeoServiceProvider extends BaseModuleServiceProvider
{
    public function register()
    {
        parent::register();

        $this->registerRouter();

        $this->app->singleton(MetaRepositoryInterface::class, function () {
            return new MetaRepository(new Meta());
        });

        $this->app->singleton(UrlRepositoryInterface::class, function () {
            return new UrlRepository(new Url());
        });

        $this->app->singleton(PreRedirectRepositoryInterface::class, function () {
            return new PreRedirectRepository(new PreRedirect());
        });

        $this->app->singleton(ErrorRedirectRepositoryInterface::class, function () {
            return new ErrorRedirectRepository(new ErrorRedirect());
        });
    }

    public function boot()
    {
        parent::boot();

        Blade::include('seo::admin.field', 'seo');
        Blade::include('seo::meta', 'seometa');
    }

    protected function registerRouter()
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('seo.friendly', SeoFriendlyUrlRewriteMiddleware::class);
        $router->aliasMiddleware('seo.preredirect', PreRedirectMiddleware::class);
    }
}
