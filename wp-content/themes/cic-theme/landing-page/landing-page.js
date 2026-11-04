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

// Project antenna tips from the artwork's own coordinates using the image's cover sizing.
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('telecomCanvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const hero = canvas.closest('.hero-section');
    const artworkImage = hero.querySelector('.hero-building');
    if (!artworkImage) return;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const artwork = {
        width: Number(artworkImage.getAttribute('width')),
        height: Number(artworkImage.getAttribute('height'))
    };
    const antennaPoints = JSON.parse(artworkImage.dataset.signalPoints);
    const pointer = { x: 0, y: 0, active: false, lastEmission: -1 };
    const pointerWaves = [];
    let stars = [];
    let width = 0;
    let height = 0;
    let sources = [];
    let animationFrame;
    let lastTime = null;
    let elapsed = 0;
    let imageScale = 1;
    let imageOffsetX = 0;
    let imageOffsetY = 0;

    function project(x, y) {
        return { x: imageOffsetX + x * imageScale, y: imageOffsetY + y * imageScale };
    }

    function skylineHeight(x) {
        // Keep stars above the side trees/towers and the triangular building roof.
        if (x < 650 || x > 1490) return 195;
        if (x < 1050) return 470 + (118 - 470) * (x - 650) / 400;
        if (x <= 1123) return 118;
        return 118 + (465 - 118) * (x - 1123) / 367;
    }

    function sketchRing(x, y, radius, phase, alpha) {
        for (let stroke = 0; stroke < 2; stroke++) {
            ctx.beginPath();
            for (let step = 0; step <= 96; step++) {
                const angle = step / 96 * Math.PI * 2;
                const roughness = Math.sin(angle * 17 + phase) * 1.15
                    + Math.sin(angle * 31 - phase * 1.7) * 0.55;
                const distance = radius + roughness + stroke * 1.4;
                const px = x + Math.cos(angle) * distance;
                const py = y + Math.sin(angle) * distance;
                if (step === 0) ctx.moveTo(px, py);
                else ctx.lineTo(px, py);
            }
            ctx.strokeStyle = `rgba(224, 229, 229, ${alpha * (stroke ? 0.23 : 0.65)})`;
            ctx.lineWidth = stroke ? 0.55 : 0.85;
            ctx.stroke();
        }
    }

    function drawStars() {
        if (!width || !height) return;
        stars.forEach(star => {
            const x = (star.x * width + elapsed * star.speed) % width;
            const y = (star.y * height * 0.72 - elapsed * star.speed * 0.35 % height + height) % height;
            const sceneX = (x - imageOffsetX) / imageScale;
            const skyBottom = project(sceneX, skylineHeight(sceneX) - 20).y;
            const clearance = skyBottom - y - star.radius;
            if (clearance <= 0) return;
            const fade = Math.min(1, clearance / 10, y / 8);
            const twinkle = 0.75 + 0.25 * Math.sin(elapsed * 0.8 + star.phase);
            ctx.beginPath();
            ctx.arc(x, y, star.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(228, 230, 228, ${star.alpha * fade * twinkle})`;
            ctx.fill();
        });
    }

    function drawScene() {
        ctx.clearRect(0, 0, width, height);
        drawStars();
        sources.forEach((source, index) => {
            // Three lightly roughened rings emitted from each tower's antenna tip.
            for (let ring = 0; ring < 3; ring++) {
                const progress = (elapsed / 5.5 + ring / 3 + index * 0.08 + 0.05) % 1;
                const radius = 6 + progress * Math.max(width * 0.2, 135);
                sketchRing(source.x, source.y, radius, index * 2 + ring, 0.72 * (1 - progress) ** 1.5);
            }
            ctx.beginPath();
            ctx.arc(source.x, source.y, 2, 0, Math.PI * 2);
            ctx.fillStyle = '#e4e5e1';
            ctx.fill();
        });
        for (let i = pointerWaves.length - 1; i >= 0; i--) {
            const wave = pointerWaves[i];
            const progress = (elapsed - wave.started) / 2.8;
            if (progress >= 1) {
                pointerWaves.splice(i, 1);
                continue;
            }
            sketchRing(wave.x, wave.y, 4 + progress * Math.min(width * 0.3, 340), 3, 0.7 * (1 - progress) ** 1.5);
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
        // Match object-fit: cover and centered placement in landing-page.css.
        imageScale = Math.max(width / artwork.width, height / artwork.height);
        imageOffsetX = (width - artwork.width * imageScale) / 2;
        imageOffsetY = (height - artwork.height * imageScale) / 2;
        sources = antennaPoints.map(([x, y]) => project(x, y));
        stars = Array.from({ length: Math.max(28, Math.min(140, Math.round(width / 14))) }, () => ({
            x: Math.random(),
            y: Math.random(),
            radius: 0.5 + Math.random() * 0.9,
            speed: 2 + Math.random() * 3,
            alpha: 0.35 + Math.random() * 0.45,
            phase: Math.random() * Math.PI * 2
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
