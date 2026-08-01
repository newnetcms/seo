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

    function renderText($el, animate) {
        let $input = $($el.data('seo-text'));
        if (!$input.length) {
            return;
        }

        let value = $.trim($input.val());
        let text = value || $el.data('placeholder');

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
        let $input = $($el.data('seo-counter'));
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

    function setImagePreview(name, url) {
        let $el = $('[data-seo-image="' + name + '"]');
        if (!$el.length) {
            return;
        }

        if (url) {
            $el.css('background-image', 'url(' + url + ')').removeClass('is-empty');
        } else {
            $el.css('background-image', '').addClass('is-empty');
        }
    }

    $('[data-seo-text]').each(function () {
        renderText($(this));
    });

    $('[data-seo-counter]').each(function () {
        renderCounter($(this));
    });

    $(document).on('input change', function (e) {
        let id = $(e.target).attr('id');
        if (!id) {
            return;
        }

        $('[data-seo-text="#' + id + '"]').each(function () {
            renderText($(this), true);
        });

        $('[data-seo-counter="#' + id + '"]').each(function () {
            renderCounter($(this));
        });
    });

    $('[data-seo-image]').each(function () {
        let name = $(this).data('seo-image');
        let $img = $('.component-' + name + ' .media-preview img').first();
        if ($img.length) {
            setImagePreview(name, $img.attr('src'));
        }
    });

    $(document).on('MediaUploaded', function (e, data) {
        if (!data || !data.name) {
            return;
        }
        setImagePreview(data.name, data.file && data.file.thumb);
    });

    $(document).on('click', '.component-seo_meta_og_image .remove-media', function () {
        setImagePreview('seo_meta_og_image', null);
    });

    $(document).on('click', '.component-seo_meta_twitter_image .remove-media', function () {
        setImagePreview('seo_meta_twitter_image', null);
    });
});
