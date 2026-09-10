/**
 * Landing Page Specific JavaScript
 * - Hero Slider cycling
 * - News & Events tab switching & smart continuous vertical scroll
 */
jQuery(document).ready(function($) {
    'use strict';

    // Hero Slider
    var slides = $('.hero-slide');
    if (slides.length > 1) {
        var currentSlide = 0;
        setInterval(function() {
            slides.eq(currentSlide).removeClass('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides.eq(currentSlide).addClass('active');
        }, 5000);
    }

    // Smart News & Events Scroll Handler
    function initNewsEventsScroll() {
        $('.circular-scroll-wrapper').each(function() {
            var $wrapper = $(this);
            var $track = $wrapper.find('.circular-scroll-track');
            if (!$track.length) return;

            // Cache original single-copy HTML
            if (!$track.data('original-html')) {
                $track.data('original-html', $track.html());
            }

            // Always reset to original single copy before measuring
            $track.html($track.data('original-html'));
            $track.removeClass('is-scrolling').removeAttr('style');
            $wrapper.removeClass('has-overflow-mask');

            // Only measure and animate if wrapper is currently visible
            if ($wrapper.is(':visible')) {
                var wrapperHeight = $wrapper.innerHeight();
                var contentHeight = $track[0].scrollHeight;

                // Only cycle/scroll if content exceeds visible window
                if (contentHeight > wrapperHeight + 4) {
                    var originalHtml = $track.data('original-html');
                    $track.append(originalHtml);

                    var trackEl = $track[0];
                    var gap = parseFloat(window.getComputedStyle(trackEl).rowGap || window.getComputedStyle(trackEl).gap) || 6;
                    var scrollDistance = -(contentHeight + gap);
                    var duration = Math.max(14, Math.round(contentHeight / 22));

                    trackEl.style.setProperty('--scroll-distance', scrollDistance + 'px');
                    trackEl.style.setProperty('--scroll-duration', duration + 's');
                    $track.addClass('is-scrolling');
                    $wrapper.addClass('has-overflow-mask');
                }
            }
        });
    }

    // News/Events tab switching
    $('.news-tab-btn, .tab-btn').off('click').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('tab');
        $('.news-tab-btn, .tab-btn').removeClass('active');
        $(this).addClass('active');
        if (target) {
            $('.news-tab-content').hide().removeClass('active');
            var $targetTab = $('#tab-' + target);
            $targetTab.stop(true, true).fadeIn(180, function() {
                initNewsEventsScroll();
            }).addClass('active');
        }
    });

    // Initial calculation on DOM ready and window load
    initNewsEventsScroll();
    $(window).on('load', function() {
        initNewsEventsScroll();
    });

    // Recalculate on window resize / orientation change (responsive on mobile & dynamic screens)
    var resizeTimer;
    $(window).on('resize orientationchange', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            initNewsEventsScroll();
        }, 150);
    });
});


