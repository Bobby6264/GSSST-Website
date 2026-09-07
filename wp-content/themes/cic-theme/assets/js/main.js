jQuery(document).ready(function($) {
    // Dark mode initialization & toggle
    if (localStorage.getItem('gssst-theme') === 'dark') {
        $('body').addClass('dark-mode');
        $('.dark-mode-toggle i').removeClass('fa-sun fa-moon fa-snowflake').addClass('fa-moon');
    }

    $('.dark-mode-toggle').on('click', function() {
        $('body').toggleClass('dark-mode');
        var isDark = $('body').hasClass('dark-mode');
        localStorage.setItem('gssst-theme', isDark ? 'dark' : 'light');
        $('.dark-mode-toggle i').removeClass('fa-sun fa-moon fa-snowflake')
            .addClass(isDark ? 'fa-moon' : 'fa-sun');
    });

    // Mobile menu toggle
    $('.mobile-menu-toggle').on('click', function(e) {
        e.stopPropagation();
        $(this).toggleClass('active');
        $('.top-menu').toggleClass('mobile-open');
        $('.sub-nav-bar').toggleClass('mobile-open');
    });

    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.top-bar, .sub-nav-bar').length) {
            $('.mobile-menu-toggle').removeClass('active');
            $('.top-menu').removeClass('mobile-open');
            $('.sub-nav-bar').removeClass('mobile-open');
        }
    });

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
        $('.news-tab-btn, .tab-btn').removeClass('active');
        $(this).addClass('active');
        var target = $(this).data('tab');
        if (target) {
            $('.news-tab-content').hide().removeClass('active');
            $('#tab-' + target).fadeIn(200).addClass('active');
        }
    });
});
