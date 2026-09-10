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

    // Mobile dropdown toggle inside top-menu
    $('.top-bar .has-dropdown > a, .top-bar .has-submenu > a').on('click', function(e) {
        if ($(window).width() <= 768 || $('.top-bar').hasClass('nav-collapsed')) {
            var href = $(this).attr('href');
            var $parent = $(this).parent();
            var $dropdown = $parent.children('.dropdown-menu, .dropdown-submenu');
            if ($dropdown.length > 0) {
                if (!href || href === '#' || href === 'javascript:void(0)' || $(e.target).closest('.nav-arrow').length > 0) {
                    e.preventDefault();
                    $parent.toggleClass('mobile-sub-open');
                }
            }
        }
    });

    // Close mobile menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.top-bar, .sub-nav-bar').length) {
            $('.mobile-menu-toggle').removeClass('active');
            $('.top-menu').removeClass('mobile-open');
            $('.sub-nav-bar').removeClass('mobile-open');
        }
    });

    // Dynamic Navbar Collapse Calculation
    var cachedRequiredNavWidth = 0;

    function calculateRequiredNavWidth() {
        var $topBar = $('.top-bar');
        if (!$topBar.length) return 0;

        var $logo = $topBar.find('.logo-area');
        var $menu = $topBar.find('.top-menu');
        if (!$logo.length || !$menu.length) return 0;

        // Create an offscreen wrapper styled exactly like desktop .top-bar to calculate natural unconstrained layout
        var $ghostContainer = $('<div class="top-bar" style="position:fixed !important; top:-9999px !important; left:-9999px !important; visibility:hidden !important; pointer-events:none !important; width:max-content !important; max-width:none !important; z-index:-9999 !important; display:block !important; padding:0 !important; margin:0 !important; border:none !important;"></div>');
        var $ghostInner = $('<div style="display:flex !important; flex-direction:row !important; align-items:center !important; width:max-content !important; max-width:none !important; gap:22px !important;"></div>');

        // Clone logo with desktop sizing
        var $ghostLogo = $logo.clone().css({
            'display': 'flex !important',
            'width': 'max-content !important',
            'max-width': 'none !important',
            'flex-shrink': '0 !important'
        });
        $ghostLogo.find('.logo-title').css({
            'font-size': '32px !important'
        });

        // Clone menu with desktop row styling
        var $ghostMenu = $menu.clone().removeClass('mobile-open').css({
            'display': 'flex !important',
            'width': 'max-content !important',
            'max-width': 'none !important',
            'flex-direction': 'row !important',
            'position': 'static !important',
            'gap': '22px !important'
        });
        $ghostMenu.find('ul.top-nav-links, ul.menu').css({
            'display': 'flex !important',
            'flex-direction': 'row !important',
            'width': 'max-content !important',
            'max-width': 'none !important',
            'gap': '4px !important'
        });
        $ghostMenu.find('ul.top-nav-links > li, ul.menu > li').css({
            'display': 'inline-block !important',
            'width': 'auto !important'
        });
        $ghostMenu.find('ul.top-nav-links > li > a, ul.menu > li > a').css({
            'display': 'inline-flex !important',
            'white-space': 'nowrap !important'
        });

        $ghostInner.append($ghostLogo).append($ghostMenu);
        $ghostContainer.append($ghostInner);
        $('body').append($ghostContainer);

        var totalWidth = Math.ceil($ghostInner[0].getBoundingClientRect().width);
        $ghostContainer.remove();

        // Add 28px safety buffer so elements never bump or wrap right before collapse
        return totalWidth + 28;
    }

    function checkNavCollapse() {
        var $topBar = $('.top-bar');
        if (!$topBar.length) return;

        if (!cachedRequiredNavWidth) {
            cachedRequiredNavWidth = calculateRequiredNavWidth();
        }

        var winWidth = $(window).width();

        // Phone screens <= 768px always collapse
        if (winWidth <= 768) {
            $topBar.addClass('nav-collapsed');
            return;
        }

        // Available width inside container for desktop layout
        var pad = (winWidth <= 1024) ? 48 : 80;
        var availableWidth = (winWidth * 0.95) - pad;

        if (availableWidth < cachedRequiredNavWidth) {
            $topBar.addClass('nav-collapsed');
        } else {
            if ($topBar.hasClass('nav-collapsed')) {
                $topBar.removeClass('nav-collapsed');
                $('.mobile-menu-toggle').removeClass('active');
                $('.top-menu').removeClass('mobile-open');
                $('.top-bar .has-dropdown, .top-bar .has-submenu').removeClass('mobile-sub-open');
            }
        }
    }

    // Initial check on document ready
    cachedRequiredNavWidth = calculateRequiredNavWidth();
    checkNavCollapse();

    // Re-verify once custom web fonts are ready
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(function() {
            cachedRequiredNavWidth = calculateRequiredNavWidth();
            checkNavCollapse();
        });
    }

    // Re-verify on window load (after images and stylesheets finish loading)
    $(window).on('load', function() {
        cachedRequiredNavWidth = calculateRequiredNavWidth();
        checkNavCollapse();
    });

    // Check on window resize
    $(window).on('resize orientationchange', function() {
        checkNavCollapse();
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
