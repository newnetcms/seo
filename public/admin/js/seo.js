$(document).ready(function () {
    'use strict';

    $('[name="seourl[request_path]"]').on('change', function () {
        let slug = $(this).val();
        if (slug) {
            let url = window.location.origin + '/' + slug.replaceAll(/^\//g, '');
            let html = `<span class="helper-block seourl-preview"><a href="${url}" target="_blank">${url} <i class="fas fa-external-link-alt"></i></a></span>`;

            if ($(this).next('.seourl-preview').length) {
                $(this).next('.seourl-preview').html(html);
            } else {
                $(this).after($(html));
            }
        } else {
            $(this).next('.seourl-preview').remove();
        }
    }).trigger('change');

    if ($('[data-seo-text], [data-seo-counter], [data-seo-image]').length === 0) {
        return;
    }

    function flash($el) {
        $el.removeClass('seo-flash');
        if ($el[0]) {
            void $el[0].offsetWidth;
        }
        $el.addClass('seo-flash');
    }

    // Selectors tried in order until one has a live value, mirroring the
    // ?: fallback chain rendered server-side in seo::meta (meta.blade.php).
    function textSourceSelectors($el) {
        let selectors = [$el.attr('data-seo-text')];
        let fallback = $el.attr('data-seo-text-fallback');
        if (fallback) {
            selectors = selectors.concat(fallback.split('||'));
        }
        return selectors.filter(Boolean);
    }

    function resolveText($el) {
        let selectors = textSourceSelectors($el);
        for (let i = 0; i < selectors.length; i++) {
            let $input = $(selectors[i]);
            if ($input.length) {
                let val = $.trim($input.val());
                if (val) {
                    return val;
                }
            }
        }
        return $el.attr('data-seo-static-fallback') || '';
    }

    function renderText($el, animate) {
        let $primary = $($el.attr('data-seo-text'));
        if (!$primary.length) {
            return;
        }

        let value = resolveText($el);
        let text = value || $el.attr('data-placeholder');

        if ($el.text() === text) {
            return;
        }

        $el.text(text);
        $el.toggleClass('is-empty', !value);

        if (animate) {
            flash($el);
        }
    }

    function renderCounter($el) {
        let $input = $($el.attr('data-seo-counter'));
        if (!$input.length) {
            return;
        }

        let length = $.trim($input.val()).length;
        let limit = parseInt($el.data('limit'), 10) || 0;

        $el.find('.seo-char-counter-value').text(length);
        $el.removeClass('is-ok is-warning is-danger');

        if (!limit) {
            return;
        }

        if (length === 0 || length <= limit * 0.85) {
            $el.addClass('is-ok');
        } else if (length <= limit) {
            $el.addClass('is-warning');
        } else {
            $el.addClass('is-danger');
        }
    }

    // Cache of "current uploaded URL" per media field name, so an image
    // preview can fall back to another field's media (e.g. og_image -> image)
    // the same way meta.blade.php falls back to object_get($item, 'image').
    let mediaUrls = {};

    function probeMediaUrl(name) {
        let $img = $('[class~="component-' + name + '"] .media-preview img').first();
        return $img.length ? $img.attr('src') : null;
    }

    function renderImage($el) {
        let name = $el.attr('data-seo-image');
        let fallbackName = $el.attr('data-seo-image-fallback');
        let staticFallback = $el.attr('data-seo-image-static-fallback');

        let url = mediaUrls[name] || (fallbackName ? mediaUrls[fallbackName] : null) || staticFallback || null;

        if (url) {
            $el.css('background-image', 'url(' + url + ')').removeClass('is-empty');
        } else {
            $el.css('background-image', '').addClass('is-empty');
        }
    }

    function renderAllImages() {
        $('[data-seo-image]').each(function () {
            renderImage($(this));
        });
    }

    $('[data-seo-text]').each(function () {
        renderText($(this));
    });

    $('[data-seo-counter]').each(function () {
        renderCounter($(this));
    });

    $(document).on('input change', function (e) {
        $('[data-seo-text]').each(function () {
            let $el = $(this);
            let matched = textSourceSelectors($el).some(function (sel) {
                return $(sel)[0] === e.target;
            });
            if (matched) {
                renderText($el, true);
            }
        });

        $('[data-seo-counter]').each(function () {
            let $el = $(this);
            if ($($el.attr('data-seo-counter'))[0] === e.target) {
                renderCounter($el);
            }
        });
    });

    $('[data-seo-image]').each(function () {
        let name = $(this).attr('data-seo-image');
        if (!(name in mediaUrls)) {
            mediaUrls[name] = probeMediaUrl(name);
        }

        let fallbackName = $(this).attr('data-seo-image-fallback');
        if (fallbackName && !(fallbackName in mediaUrls)) {
            mediaUrls[fallbackName] = probeMediaUrl(fallbackName);
        }
    });
    renderAllImages();

    $(document).on('MediaUploaded', function (e, data) {
        if (!data || !data.name) {
            return;
        }
        mediaUrls[data.name] = data.file && data.file.thumb;
        renderAllImages();
    });

    $(document).on('click', '.media-form-group .remove-media', function () {
        let name = $(this).closest('.media-form-group').find('.inputFileMedia').data('media-name');
        if (name) {
            mediaUrls[name] = null;
            renderAllImages();
        }
    });
});
