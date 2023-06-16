<?php

use Newnet\Seo\Http\Controllers\Admin\PreRedirectController;
use Newnet\Seo\Http\Controllers\Admin\ErrorRedirectController;
use Newnet\Seo\Http\Controllers\Admin\SeoSettingController;

Route::prefix('seo')
    ->name('seo.admin.')
    ->middleware('admin.acl')
    ->group(function () {
        Route::resource('pre-redirect', PreRedirectController::class);
        Route::resource('error-redirect', ErrorRedirectController::class);

        Route::get('setting', [SeoSettingController::class, 'index'])
            ->name('setting.index');

        Route::post('setting/save', [SeoSettingController::class, 'save'])
            ->name('setting.save');
    });
