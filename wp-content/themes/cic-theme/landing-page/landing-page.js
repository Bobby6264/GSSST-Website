/**
 * Landing Page Specific JavaScript
 * - Animated Telecom Hero Background
 * - News & Events tab switching & smart continuous vertical scroll
 */
jQuery(document).ready(function($) {
    'use strict';

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

// Canvas Animation Logic
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('telecomCanvas');
    if (!canvas) return; // Exit if not on landing page
    
    const ctx = canvas.getContext('2d');
    const heroSection = document.querySelector('.hero-section');

    let width, height;
    let towers = [];

    function resizeCanvas() {
        width = canvas.width = canvas.parentElement.clientWidth;
        height = canvas.height = canvas.parentElement.clientHeight;
        
        towers = [
            { x: width * 0.15, y: height * 0.55, baseWidth: 50 }, 
            { x: width * 0.5,  y: height * 0.35, baseWidth: 100}, 
            { x: width * 0.85, y: height * 0.5,  baseWidth: 60 }  
        ];
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    let time = 0;
    const signals = [];
    const particles = [];
    const mouse = { x: 0, y: 0, active: false };
    
    heroSection.addEventListener('mousemove', (e) => {
        const rect = heroSection.getBoundingClientRect();
        mouse.x = e.clientX - rect.left;
        mouse.y = e.clientY - rect.top;
        mouse.active = true;
    });

    heroSection.addEventListener('mouseleave', () => {
        mouse.active = false;
    });

    heroSection.addEventListener('mousedown', () => {
        if (mouse.active) {
            signals.push(new Signal(mouse.x, mouse.y, '0, 255, 200', 1.5)); 
        }
    });

    class Signal {
        constructor(x, y, rgbColor = '100, 200, 255', speed = 1.0) {
            this.x = x;
            this.y = y;
            this.radius = 0;
            this.maxRadius = width > height ? width * 0.6 : height * 0.6;
            this.speed = speed;
            this.alpha = 0.8;
            this.rgbColor = rgbColor; 
        }

        update() {
            this.radius += this.speed;
            let progress = this.radius / this.maxRadius;
            this.alpha = 0.8 * Math.pow(1 - progress, 2);
        }

        draw() {
            if (this.alpha <= 0.01) return; 
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.strokeStyle = `rgba(${this.rgbColor}, ${this.alpha})`;
            ctx.lineWidth = 1.5;
            ctx.stroke();
        }
    }

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.size = Math.random() * 1.5;
            this.speedY = Math.random() * -0.2 - 0.05; 
            this.alpha = Math.random() * 0.8 + 0.1;
        }
        update() {
            this.y += this.speedY;
            if (this.y < 0) {
                this.y = height;
                this.x = Math.random() * width;
            }
        }
        draw() {
            ctx.fillStyle = `rgba(255, 255, 255, ${this.alpha})`;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    for(let i = 0; i < 250; i++) {
        particles.push(new Particle());
    }

    function drawTower(x, y, baseWidth, colorOffset) {
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.35)';
        ctx.lineWidth = 2;
        ctx.beginPath();
        
        ctx.moveTo(x - baseWidth/2, height);
        ctx.lineTo(x, y);
        ctx.lineTo(x + baseWidth/2, height);

        let steps = 4;
        let towerHeight = height - y;
        for(let i = 1; i <= steps; i++) {
            let fraction = i / (steps + 1);
            let crossY = y + (towerHeight * fraction);
            let crossWidth = baseWidth * fraction;
            
            ctx.moveTo(x - crossWidth/2, crossY);
            ctx.lineTo(x + crossWidth/2, crossY);
        }
        ctx.stroke();

        let hue = (time + colorOffset) % 360;
        let lightColor = `hsl(${hue}, 100%, 60%)`;

        ctx.fillStyle = lightColor;
        ctx.shadowBlur = 12;
        ctx.shadowColor = lightColor;
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.shadowBlur = 0; 
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);

        if (time % 200 === 0) {
            towers.forEach(tower => {
                signals.push(new Signal(tower.x, tower.y, '100, 200, 255')); 
            });
        }

        if (mouse.active && time % 100 === 0) {
            signals.push(new Signal(mouse.x, mouse.y, '0, 255, 200', 1.2)); 
        }

        particles.forEach(p => {
            p.update();
            p.draw();
        });

        for (let i = signals.length - 1; i >= 0; i--) {
            let s = signals[i];
            s.update();
            s.draw();
            if (s.alpha <= 0.01) {
                signals.splice(i, 1);
            }
        }

        towers.forEach(tower => {
            drawTower(tower.x, tower.y, tower.baseWidth, tower.x);
        });

        time++;
        requestAnimationFrame(animate);
    }

    animate();
});
