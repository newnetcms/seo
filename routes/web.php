<?php

use Newnet\Seo\Http\Controllers\Web\SitemapController;
use Newnet\Seo\Http\Controllers\Web\UrlRewriteController;

Route::prefix(LaravelLocalization::setLocale())
    ->middleware([
        'localizationRedirect',
    ])
    ->group(function () {
        Route::get('/', UrlRewriteController::class);
        Route::fallback(UrlRewriteController::class);
    });

Route::get('sitemap.xml', SitemapController::class);
