<?php

namespace Newnet\Seo\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Newnet\Seo\Models\Meta;
use Newnet\Seo\Models\Url;

trait SeoableTrait
{
    protected $seoableAttributes = [];

    protected $modelSeourls;

    abstract public function getUrl();

    public static function bootSeoableTrait()
    {
        static::deleting(function ($model) {
            !$model->seometa || $model->seometa->delete();

            foreach ($model->seourls as $seourl) {
                $seourl->delete();
            }
        });

        static::saved(function (self $model) {
            $model->saveSeourlAttribute();
            $model->saveSeometaAttribute();
        });
    }

    public function initializeSeoableTrait()
    {
        $this->fillable[] = 'seometa';
        $this->fillable[] = 'seourl';
        $this->fillable[] = 'url';
    }

    public function seourl()
    {
        return $this
            ->morphOne(Url::class, 'urlable')
            ->where('locale', get_current_edit_locale());
    }

    public function seourls()
    {
        return $this->morphMany(Url::class, 'urlable');
    }

    public function seometa()
    {
        return $this->morphOne(Meta::class, 'metable');
    }

    public function saveSeometaAttribute()
    {
        if (isset($this->seoableAttributes['seometa'])) {
            $value = $this->seoableAttributes['seometa'];

            if ($this->seometa) {
                $this->seometa->update($value);
            } else {
                $this->seometa()->create($value);
            }
        }
    }

    public function saveSeourlAttribute()
    {
        if (isset($this->seoableAttributes['seourl'])) {
            $value = $this->seoableAttributes['seourl'];

            $slug = get_safe_slug($this->name);

            $value['target_path'] = parse_url($this->getUrl(), PHP_URL_PATH);
            $value['request_path'] = $value['request_path'] ?? $slug;

            // `seourl` is a morphOne - Eloquent lazy-loads and CACHES it on
            // first access. The very first time this runs (right inside the
            // `saved` event of the model's OWN first insert), no Url row
            // exists yet, so the check below caches `null`. That cache is
            // never invalidated, so a caller that saves the SAME model
            // instance again later in the same request (e.g. this module's
            // AI-content pipeline: DraftContentWriter::createDraft() saves
            // once, then PreviewDraftPublisher::publish() saves the same
            // object again via applyFields()) always saw a falsy `$this->
            // seourl`, took the create() branch again, and produced a
            // duplicate seo__urls row every single time. setRelation() here
            // pushes the row we just wrote onto that cache so a subsequent
            // save on this same instance correctly finds it and updates
            // instead of re-creating.
            $seourl = $this->seourl
                ? tap($this->seourl)->update($value)
                : $this->seourl()->create($value);

            $this->setRelation('seourl', $seourl);
        }
    }

    public function setSeometaAttribute($value)
    {
        $this->seoableAttributes['seometa'] = $value;
    }

    public function setSeourlAttribute($value)
    {
        $this->seoableAttributes['seourl'] = $value;
    }

    public function getUrlAttribute()
    {
        if (method_exists($this, 'getUrl')) {
            if (config('cms.seo.enable_cache_url')) {
                $locale = App::getLocale();
                $cache_key = 'url_'.$locale.'_'.get_class($this).'_'.$this->id;
                return Cache::driver(config('cms.seo.cache_driver'))->rememberForever($cache_key, function () {
                    return $this->generateUrl();
                });
            } else {
                return $this->generateUrl();
            }
        }

        return $this->slug ? LaravelLocalization::localizeURL($this->slug) : null;
    }

    public function setUrlAttribute($value)
    {
        $this->seoableAttributes['seourl']['request_path'] = $value;
    }

    protected function generateUrl()
    {
        $locale = App::getLocale();

        $targetPath = ltrim(parse_url($this->getUrl(), PHP_URL_PATH), '/') ?: '/';

        if (config('cms.seo.enable_fallback_seo_url')) {
            $seourls = $this->getSeoUrls($targetPath);

            $seourl = $seourls->where('locale', $locale)->first();
            if (!$seourl) {
                $seourl = $seourls->first();
            }

            $seoUrl = object_get($seourl, 'request_path');

            return $seoUrl ? LaravelLocalization::localizeURL($seoUrl) : null;
        } else {
            $seoUrl = $this->seourl ? $this->seourl->request_path : null;

            return $seoUrl ? LaravelLocalization::localizeURL($seoUrl) : null;
        }
    }

    protected function getSeoUrls(string $targetPath)
    {
        if (!$this->modelSeourls) {
            $this->modelSeourls = Url::where('target_path', $targetPath)->get();
        }

        return $this->modelSeourls;
    }
}
