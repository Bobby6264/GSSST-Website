jQuery(document).ready(function($) {
    $('.dark-mode-toggle').on('click', function() {
        $('body').toggleClass('dark-mode');
        // A simple dark mode implementation
        if ($('body').hasClass('dark-mode')) {
            $(':root').css('--light-bg', '#121212');
            $(':root').css('--white', '#1e1e1e');
            $(':root').css('--text-color', '#ddd');
            $(this).find('i').removeClass('fa-moon').addClass('fa-sun');
        } else {
            $(':root').css('--light-bg', '#f8f9fa');
            $(':root').css('--white', '#fff');
            $(':root').css('--text-color', '#333');
            $(this).find('i').removeClass('fa-sun').addClass('fa-moon');
        }
    });
});


    // Hero Slider
    let slides = $('.hero-slide');
    if(slides.length > 1) {
        let currentSlide = 0;
        setInterval(function() {
            slides.eq(currentSlide).removeClass('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides.eq(currentSlide).addClass('active');
        }, 5000);
    }
