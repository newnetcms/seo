@extends('admin::master')

@section('meta_title', __('seo::setting.index.page_title'))

@section('page_title', __('seo::setting.index.page_title'))

@section('page_subtitle', __('seo::setting.index.page_subtitle'))

@section('breadcrumb')
    <nav aria-label="breadcrumb" class="col-sm-4 order-sm-last mb-3 mb-sm-0 p-0 ">
        <ol class="breadcrumb d-inline-flex font-weight-600 fs-13 bg-white mb-0 float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">{{ trans('dashboard::message.index.breadcrumb') }}</a></li>
            <li class="breadcrumb-item active">{{ trans('seo::setting.index.breadcrumb') }}</li>
        </ol>
    </nav>
@stop

@section('content')
    <form action="{{ route('setting.admin.setting.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 font-weight-600 mb-0">
                            {{ __('seo::setting.index.page_title') }}
                        </h6>
                    </div>
                    <div class="text-right">
                        <div class="btn-group">
                            <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        @input(['name' => 'seo_meta_title', 'label' => __('seo::meta.title')])
                        @textarea(['name' => 'seo_meta_description', 'label' => __('seo::meta.description')])
                        @textarea(['name' => 'seo_meta_keywords', 'label' => __('seo::meta.keywords')])
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-end">
                            <div class="seo-preview-card">
                                <div class="seo-preview-card-header">
                                    <span class="seo-preview-badge seo-preview-badge-google">
                                        <svg viewBox="0 0 48 48">
                                            <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 12.955 4 4 12.955 4 24s8.955 20 20 20c11.045 0 20-8.955 20-20 0-1.341-.138-2.65-.389-3.917z"></path>
                                            <path fill="#FF3D00" d="M6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4 16.318 4 9.656 8.337 6.306 14.691z"></path>
                                            <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.211 35.091 26.715 36 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"></path>
                                            <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303c-.792 2.237-2.231 4.166-4.087 5.571l.003-.002 6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"></path>
                                        </svg>
                                    </span>
                                    <span class="seo-preview-card-title">{{ __('seo::setting.preview.google') }}</span>
                                </div>
                                <div class="seo-preview-card-body">
                                    <div class="cms-seo-preview">
                                        <div class="cms-seo-preview-site">
                                            <span class="cms-seo-favicon">
                                                @if($favicon = get_setting_media_url('favicon'))
                                                    <img src="{{ $favicon }}" alt="favicon">
                                                @else
                                                    <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20zm7.94 9h-3.1a15.65 15.65 0 00-1.11-5.32A8.03 8.03 0 0119.94 11zM12 4.06c.98 1.2 2.13 3.28 2.42 6.94H9.58c.29-3.66 1.44-5.74 2.42-6.94zM9.58 13h4.84c-.29 3.66-1.44 5.74-2.42 6.94-.98-1.2-2.13-3.28-2.42-6.94zM8.27 5.68A15.65 15.65 0 007.16 11h-3.1a8.03 8.03 0 014.21-5.32zM4.06 13h3.1c.14 1.87.5 3.66 1.11 5.32A8.03 8.03 0 014.06 13zm11.67 5.32c.61-1.66.97-3.45 1.11-5.32h3.1a8.03 8.03 0 01-4.21 5.32z"></path></svg>
                                                @endif
                                            </span>
                                            <div class="cms-seo-site-meta">
                                                <div class="cms-seo-site-name">{{ setting('site_title_short') ?: config('app.name') }}</div>
                                                <div class="cms-seo-preview-link">{{ url('/') }}</div>
                                            </div>
                                        </div>
                                        <div class="cms-seo-preview-title" data-seo-text="#seo_meta_title" data-placeholder="{{ __('seo::setting.preview.title_placeholder') }}"></div>
                                        <div class="cms-seo-preview-desc" data-seo-text="#seo_meta_description" data-placeholder="{{ __('seo::setting.preview.description_placeholder') }}"></div>
                                    </div>
                                </div>
                                <div class="seo-preview-card-footer">
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_title" data-limit="60">
                                        {{ __('seo::meta.title') }}: <span class="seo-char-counter-value">0</span>/60
                                    </span>
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_description" data-limit="160">
                                        {{ __('seo::meta.description') }}: <span class="seo-char-counter-value">0</span>/160
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-5">
                        @input(['name' => 'seo_meta_og_title', 'label' => __('seo::meta.og_title')])
                        @textarea(['name' => 'seo_meta_og_description', 'label' => __('seo::meta.og_description')])
                        @mediafile(['name' => 'seo_meta_og_image', 'label' => __('seo::meta.og_image'), 'helper' => __('seo::meta.og_image_helper')])
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-end">
                            <div class="seo-preview-card">
                                <div class="seo-preview-card-header">
                                    <span class="seo-preview-badge seo-preview-badge-facebook">
                                        <svg viewBox="0 0 24 24" fill="#fff">
                                            <path d="M22 12.06C22 6.505 17.523 2 12 2S2 6.505 2 12.06c0 5.02 3.657 9.184 8.438 9.94v-7.03H7.898v-2.91h2.54V9.845c0-2.522 1.492-3.916 3.777-3.916 1.094 0 2.238.197 2.238.197v2.475h-1.26c-1.243 0-1.63.775-1.63 1.57v1.888h2.773l-.443 2.91h-2.33V22c4.78-.756 8.437-4.92 8.437-9.94z"></path>
                                        </svg>
                                    </span>
                                    <span class="seo-preview-card-title">{{ __('seo::setting.preview.facebook') }}</span>
                                </div>
                                <div class="seo-preview-card-body">
                                    <div class="cms-og-preview">
                                        <div class="cms-og-preview-image is-empty" data-seo-image="seo_meta_og_image">
                                            <span class="cms-preview-image-placeholder"><i class="fas fa-image"></i> {{ __('seo::setting.preview.no_image') }}</span>
                                        </div>
                                        <div class="cms-og-preview-body">
                                            <div class="cms-og-preview-meta">{{ parse_url(url('/'), PHP_URL_HOST) }}</div>
                                            <div class="cms-og-preview-title" data-seo-text="#seo_meta_og_title" data-placeholder="{{ __('seo::setting.preview.title_placeholder') }}"></div>
                                            <div class="cms-og-preview-desc" data-seo-text="#seo_meta_og_description" data-placeholder="{{ __('seo::setting.preview.description_placeholder') }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="seo-preview-card-footer">
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_og_title" data-limit="60">
                                        {{ __('seo::meta.og_title') }}: <span class="seo-char-counter-value">0</span>/60
                                    </span>
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_og_description" data-limit="110">
                                        {{ __('seo::meta.og_description') }}: <span class="seo-char-counter-value">0</span>/110
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-5">
                        @input(['name' => 'seo_meta_twitter_title', 'label' => __('seo::meta.twitter_title')])
                        @textarea(['name' => 'seo_meta_twitter_description', 'label' => __('seo::meta.twitter_description')])
                        @mediafile(['name' => 'seo_meta_twitter_image', 'label' => __('seo::meta.twitter_image'), 'helper' => __('seo::meta.twitter_image_helper')])
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-end">
                            <div class="seo-preview-card">
                                <div class="seo-preview-card-header">
                                    <span class="seo-preview-badge seo-preview-badge-twitter">
                                        <svg viewBox="0 0 24 24" fill="#fff">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817-5.966 6.817H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                                        </svg>
                                    </span>
                                    <span class="seo-preview-card-title">{{ __('seo::setting.preview.twitter') }}</span>
                                </div>
                                <div class="seo-preview-card-body">
                                    <div class="cms-twitter-preview">
                                        <div class="cms-twitter-preview-image is-empty" data-seo-image="seo_meta_twitter_image">
                                            <span class="cms-preview-image-placeholder"><i class="fas fa-image"></i> {{ __('seo::setting.preview.no_image') }}</span>
                                        </div>
                                        <div class="cms-twitter-preview-body">
                                            <div class="cms-twitter-preview-title" data-seo-text="#seo_meta_twitter_title" data-placeholder="{{ __('seo::setting.preview.title_placeholder') }}"></div>
                                            <div class="cms-twitter-preview-desc" data-seo-text="#seo_meta_twitter_description" data-placeholder="{{ __('seo::setting.preview.description_placeholder') }}"></div>
                                            <div class="cms-twitter-preview-meta">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M11.96 14.945a.833.833 0 01-.203-.027 5.192 5.192 0 01-2.795-1.932c-.832-1.125-1.176-2.508-.968-3.893s.942-2.605 2.068-3.438l3.53-2.608a5.25 5.25 0 017.33 1.1c.83 1.127 1.175 2.51.967 3.895s-.943 2.605-2.07 3.438l-1.48 1.094a.752.752 0 01-.892-1.208l1.48-1.095a3.713 3.713 0 001.476-2.45 3.724 3.724 0 00-.69-2.778 3.745 3.745 0 00-5.23-.784l-3.53 2.608a3.72 3.72 0 00-1.475 2.45c-.15.99.097 1.975.69 2.778a3.701 3.701 0 001.992 1.377.752.752 0 01-.202 1.475z" fill="#536471"></path>
                                                    <path d="M7.27 22.054a5.24 5.24 0 01-5.193-6.019 5.21 5.21 0 012.07-3.438l1.478-1.094a.752.752 0 01.893 1.208l-1.48 1.095a3.716 3.716 0 00-1.475 2.45c-.148.99.097 1.975.69 2.778a3.745 3.745 0 005.23.785l3.528-2.608a3.744 3.744 0 00.785-5.23 3.7 3.7 0 00-1.992-1.376.75.75 0 01-.52-.927c.112-.4.528-.63.926-.522a5.19 5.19 0 012.794 1.932 5.248 5.248 0 01-1.1 7.33l-3.53 2.608a5.189 5.189 0 01-3.105 1.026z" fill="#536471"></path>
                                                </svg>
                                                {{ parse_url(url('/'), PHP_URL_HOST) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="seo-preview-card-footer">
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_twitter_title" data-limit="70">
                                        {{ __('seo::meta.twitter_title') }}: <span class="seo-char-counter-value">0</span>/70
                                    </span>
                                    <span class="seo-char-counter" data-seo-counter="#seo_meta_twitter_description" data-limit="200">
                                        {{ __('seo::meta.twitter_description') }}: <span class="seo-char-counter-value">0</span>/200
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="btn-group">
                    <button class="btn btn-success" type="submit">{{ __('core::button.save') }}</button>
                </div>
            </div>
        </div>
    </form>
@stop

@assetadd('seo.script', 'vendor/seo/admin/js/seo.js', ['jquery'])
