<?php

use Newnet\Seo\Enums\SeoAdminMenuKey;
use Newnet\Setting\SettingAdminMenuKey;

AdminMenu::addItem(__('seo::module.module_name'), [
    'id' => SeoAdminMenuKey::SEO,
    'parent' => SettingAdminMenuKey::SYSTEM,
    'icon' => 'fas fa-radar',
    'order' => 90,
]);

AdminMenu::addItem(__('seo::setting.model_name'), [
    'id' => SeoAdminMenuKey::SETTING,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.setting.index',
    'icon' => 'fal fa-cog',
    'order' => 1,
]);

AdminMenu::addItem(__('seo::pre-redirect.model_name'), [
    'id' => SeoAdminMenuKey::PRE_REDIRECT,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.pre-redirect.index',
    'icon' => 'fal fa-angle-double-right',
    'order' => 2,
]);

AdminMenu::addItem(__('seo::error-redirect.model_name'), [
    'id' => SeoAdminMenuKey::ERROR_REDIRECT,
    'parent' => SeoAdminMenuKey::SEO,
    'route' => 'seo.admin.error-redirect.index',
    'icon' => 'fad fa-angle-double-right',
    'order' => 3,
]);
