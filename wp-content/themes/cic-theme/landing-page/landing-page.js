/**
 * Landing Page Specific JavaScript
 * - Hero Slider cycling
 * - News & Events tab switching
 */
jQuery(document).ready(function($) {
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

    // News/Events tab switching
    $('.news-tab-btn, .tab-btn').on('click', function() {
        var target = $(this).data('tab');
        $('.news-tab-btn, .tab-btn').removeClass('active');
        $(this).addClass('active');
        if (target) {
            $('.news-tab-content').hide().removeClass('active');
            $('#tab-' + target).fadeIn(200).addClass('active');
        }
    });
});

