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

// Coordinates use the architectural SVG's cropped 150 70 1300 640 viewBox.
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('telecomCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const hero = canvas.closest('.hero-section');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const roofPoints = [[510, 360], [830, 120], [1110, 400]];
    const pointer = { x: 0, y: 0, active: false, lastEmission: -1 };
    const pointerWaves = [];
    let width = 0;
    let height = 0;
    let sources = [];
    let animationFrame;
    let lastTime = null;
    let elapsed = 0;

    function drawScene() {
        ctx.clearRect(0, 0, width, height);
        sources.forEach((source, index) => {
            // Three visible rings from each of three well-separated roof points.
            for (let ring = 0; ring < 3; ring++) {
                const progress = (elapsed / 5.5 + ring / 3 + index * 0.08 + 0.05) % 1;
                const radius = 6 + progress * Math.max(width * 0.24, 160);
                ctx.beginPath();
                ctx.arc(source.x, source.y, radius, 0, Math.PI * 2);
                ctx.strokeStyle = `rgba(216, 236, 255, ${0.62 * (1 - progress) ** 1.5})`;
                ctx.lineWidth = 1.2;
                ctx.stroke();
            }
            ctx.beginPath();
            ctx.arc(source.x, source.y, 2.5, 0, Math.PI * 2);
            ctx.fillStyle = '#edf6ff';
            ctx.shadowColor = '#b9dcff';
            ctx.shadowBlur = 9;
            ctx.fill();
            ctx.shadowBlur = 0;
        });
        for (let i = pointerWaves.length - 1; i >= 0; i--) {
            const wave = pointerWaves[i];
            const progress = (elapsed - wave.started) / 2.8;
            if (progress >= 1) {
                pointerWaves.splice(i, 1);
                continue;
            }
            ctx.beginPath();
            ctx.arc(wave.x, wave.y, 4 + progress * Math.min(width * 0.3, 340), 0, Math.PI * 2);
            ctx.strokeStyle = `rgba(105, 230, 218, ${0.7 * (1 - progress) ** 1.5})`;
            ctx.lineWidth = 1.3;
            ctx.stroke();
        }
    }

    function emitPointerWave() {
        if (reducedMotion.matches || document.hidden) return;
        pointerWaves.push({ x: pointer.x, y: pointer.y, started: elapsed });
        // Bound the queue even when many pointer events arrive between frames.
        if (pointerWaves.length > 24) pointerWaves.shift();
        pointer.lastEmission = elapsed;
    }

    function trackPointer(event) {
        const rect = hero.getBoundingClientRect();
        pointer.x = event.clientX - rect.left;
        pointer.y = event.clientY - rect.top;
        pointer.active = event.pointerType !== 'touch';
    }

    hero.addEventListener('pointermove', event => {
        trackPointer(event);
        if (pointer.active && elapsed - pointer.lastEmission >= 0.14) emitPointerWave();
    });
    hero.addEventListener('pointerdown', event => {
        trackPointer(event);
        emitPointerWave();
    });
    hero.addEventListener('pointerleave', () => { pointer.active = false; });
    hero.addEventListener('pointercancel', () => { pointer.active = false; });

    function resizeCanvas() {
        width = hero.clientWidth;
        height = hero.clientHeight;
        const ratio = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = Math.round(width * ratio);
        canvas.height = Math.round(height * ratio);
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        // Match preserveAspectRatio="none" and object-fit: fill exactly.
        sources = roofPoints.map(([x, y]) => ({
            x: (x - 150) / 1300 * width,
            y: (y - 70) / 640 * height
        }));
        pointerWaves.length = 0;
        pointer.active = false;
        drawScene();
    }

    function animate(timestamp) {
        if (lastTime !== null) elapsed += Math.min((timestamp - lastTime) / 1000, 0.05);
        lastTime = timestamp;
        if (pointer.active && elapsed - pointer.lastEmission >= 0.7) emitPointerWave();
        drawScene();
        animationFrame = requestAnimationFrame(animate);
    }

    function syncAnimation() {
        cancelAnimationFrame(animationFrame);
        lastTime = null;
        if (!reducedMotion.matches && !document.hidden) {
            animationFrame = requestAnimationFrame(animate);
        } else {
            pointer.active = false;
            pointerWaves.length = 0;
            drawScene();
        }
    }

    new ResizeObserver(resizeCanvas).observe(hero);
    reducedMotion.addEventListener('change', syncAnimation);
    document.addEventListener('visibilitychange', syncAnimation);
    resizeCanvas();
    syncAnimation();
});
