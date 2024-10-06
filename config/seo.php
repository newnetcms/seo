<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Pre Redirect
    |--------------------------------------------------------------------------
    |
    | This value is enable or disable pre redirect. For best performance, this
    | function will be disabled. Set it 'true' for enable pre-redirect.
    |
    */
    'enable_pre_redirect' => env('SEO_ENABLE_PRE_REDIRECT', false),

    'enable_cache_url' => env('SEO_ENABLE_CACHE_URL', false),
    'cache_driver' => env('SEO_CACHE_DRIVER'),
];
