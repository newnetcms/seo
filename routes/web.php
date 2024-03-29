<?php

use Newnet\Seo\Http\Controllers\Web\SitemapController;
use Newnet\Seo\Http\Controllers\Web\UrlRewriteController;

Route::prefix(LaravelLocalization::setLocale())
    ->middleware([
        'localizationRedirect',
        'seo.friendly',
        'seo.preredirect',
    ])
    ->group(function () {
        Route::get('/', UrlRewriteController::class);
        Route::fallback(UrlRewriteController::class);
    });

Route::get('sitemap.xml', SitemapController::class);
Route::get('seo/pre-redirect/{url}', [UrlRewriteController::class, 'checkPreRedirect']);
