"use strict";

// Threads de React Bits, reinterpretado para Saldo con Canvas 2D.
// https://reactbits.dev/backgrounds/threads
(() => {
    const canvas = document.querySelector('[data-auth-threads]');
    const context = canvas?.getContext('2d');
    if (!context) return;

    const graphic = canvas.parentElement;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const pointer = { x: 0.5, y: 0.5, strength: 0 };
    const target = { ...pointer };
    const threadCount = 38;
    const samples = 88;
    let width = 0;
    let height = 0;
    let gradients;
    let visible = true;
    let frame = 0;
    let time = 0;
    let previous = 0;

    function gradient(stops) {
        const paint = context.createLinearGradient(0, 0, width, 0);
        stops.forEach(([offset, color]) => paint.addColorStop(offset, color));
        return paint;
    }

    function draw() {
        context.clearRect(0, 0, width, height);

        for (let index = 0; index < threadCount; index++) {
            const strand = index / (threadCount - 1);
            const cross = strand - 0.5;
            const accent = strand < 0.3;
            const highlight = index % 8 === 0;
            context.strokeStyle = accent ? gradients.peach : gradients.mint;
            context.lineWidth = highlight ? 1.28 : 0.75;
            const opacity = accent ? 0.63 : 0.47;
            context.globalAlpha = (highlight ? 0.84 : opacity) + 0.14 * Math.sin(strand * Math.PI);

            context.beginPath();
            for (let point = 0; point <= samples; point++) {
                const position = point / samples;
                const envelope = Math.sin(Math.PI * position);
                const sweep = 0.72 - 0.44 * position;
                const wave = 0.15 * Math.sin(7.5 * position - 0.65 + time) * envelope;
                const fold = 0.105 * Math.cos(11 * position + strand * 3.1 - time * 0.85) * envelope;
                const separation = cross * (0.12 + 0.41 * envelope);
                let vertical = sweep + wave + fold + separation;

                if (pointer.strength > 0.002) {
                    const dx = position - pointer.x;
                    const dy = vertical - pointer.y;
                    const influence = Math.exp(-((dx * dx) / 0.015 + (dy * dy) / 0.065));
                    vertical += dy * influence * pointer.strength * 0.48;
                }

                const x = position * width;
                const y = (0.05 + vertical * 0.9) * height;
                if (point === 0) context.moveTo(x, y);
                else context.lineTo(x, y);
            }
            context.stroke();
        }
        context.globalAlpha = 1;
        graphic.dataset.rendered = '';
    }

    function animate(timestamp) {
        if (!previous || timestamp - previous >= 32) {
            if (previous) time += Math.min(timestamp - previous, 80) * 0.00048;
            previous = timestamp;
            pointer.x += (target.x - pointer.x) * 0.12;
            pointer.y += (target.y - pointer.y) * 0.12;
            pointer.strength += (target.strength - pointer.strength) * 0.12;
            draw();
        }
        frame = window.requestAnimationFrame(animate);
    }

    function syncMotion() {
        if (!width || !height || !visible || document.hidden || reducedMotion.matches) {
            window.cancelAnimationFrame(frame);
            frame = 0;
            previous = 0;

            if (reducedMotion.matches && width && height) {
                time = 0;
                pointer.strength = target.strength = 0;
                draw();
            }
        } else if (!frame) {
            frame = window.requestAnimationFrame(animate);
        }
    }

    function resize() {
        width = graphic.clientWidth;
        height = graphic.clientHeight;
        if (width && height) {
            const ratio = Math.min(window.devicePixelRatio || 1, 2, 1600 / Math.max(width, height));
            canvas.width = Math.max(1, Math.round(width * ratio));
            canvas.height = Math.max(1, Math.round(height * ratio));
            context.setTransform(canvas.width / width, 0, 0, canvas.height / height, 0, 0);
            context.lineCap = 'round';
            context.lineJoin = 'round';
            gradients = {
                mint: gradient([
                    [0, '#a1dbc300'], [0.15, '#a1dbc38c'], [0.46, '#a1dbc3'],
                    [0.78, '#b9e5d0b8'], [1, '#b9e5d000'],
                ]),
                peach: gradient([
                    [0, '#a1dbc300'], [0.16, '#a1dbc36b'], [0.48, '#e9b6a0d4'],
                    [0.76, '#f2c2ae'], [1, '#f2c2ae00'],
                ]),
            };
            draw();
        }
        syncMotion();
    }

    graphic.addEventListener('pointermove', event => {
        if (reducedMotion.matches || event.pointerType === 'touch') return;
        const bounds = graphic.getBoundingClientRect();
        if (!bounds.width || !bounds.height) return;
        target.x = (event.clientX - bounds.left) / bounds.width;
        target.y = (event.clientY - bounds.top) / bounds.height;
        target.strength = 1;
    });
    graphic.addEventListener('pointerleave', () => { target.strength = 0; });
    document.addEventListener('visibilitychange', syncMotion);
    reducedMotion.addEventListener('change', syncMotion);

    if ('IntersectionObserver' in window) {
        new IntersectionObserver(([entry]) => {
            visible = entry.isIntersecting;
            syncMotion();
        }).observe(graphic);
    }
    if ('ResizeObserver' in window) new ResizeObserver(resize).observe(graphic);
    else window.addEventListener('resize', resize);

    resize();
})();
