"use strict";

const currencyMenu = document.querySelector('.currency-menu');
if (currencyMenu) {
    document.addEventListener('pointerdown', event => {
        if (currencyMenu.open && !currencyMenu.contains(event.target)) currencyMenu.open = false;
    });
    currencyMenu.addEventListener('keydown', event => {
        if (event.key !== 'Escape' || !currencyMenu.open) return;
        event.stopPropagation();
        currencyMenu.open = false;
        currencyMenu.querySelector('summary').focus();
    });
}

const menu = document.querySelector('[data-menu]');
if (menu) {
    const closeMenu = () => {
        document.body.classList.remove('menu-open');
        menu.setAttribute('aria-expanded', 'false');
        menu.setAttribute('aria-label', 'Mostrar navegación');
    };
    menu.addEventListener('click', () => {
        if (document.body.classList.contains('menu-open')) {
            closeMenu();
            return;
        }
        document.body.classList.add('menu-open');
        menu.setAttribute('aria-expanded', 'true');
        menu.setAttribute('aria-label', 'Ocultar navegación');
        document.querySelector('#navegacion nav a')?.focus();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && document.body.classList.contains('menu-open')) {
            closeMenu();
            menu.focus();
        }
    });
}

document.querySelectorAll('form[data-confirm]').forEach(form => {
    form.addEventListener('submit', event => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
});

document.querySelectorAll('[data-category-form]').forEach(form => {
    const type = form.querySelector('[data-category-type]');
    const categories = form.querySelector('[data-category-list]');
    const detail = form.querySelector('[data-category-detail]');
    const detailInput = detail.querySelector('input');
    const syncDetail = () => {
        const show = categories.selectedOptions[0]?.hasAttribute('data-custom-category') ?? false;
        detail.hidden = !show;
        detailInput.disabled = !show;
    };
    if (type) {
        const groups = [...categories.querySelectorAll('optgroup')];
        const syncType = () => {
            const selected = categories.value;
            groups.forEach(group => group.remove());
            const options = groups.find(group => group.dataset.type === type.value);
            if (options) categories.append(options);
            categories.value = selected;
            if (!categories.value) categories.selectedIndex = 0;
            syncDetail();
        };
        type.addEventListener('change', syncType);
        syncType();
    } else {
        syncDetail();
    }
    categories.addEventListener('change', syncDetail);
});

document.querySelectorAll('[data-period]').forEach(form => {
    const month = form.querySelector('input[type="month"]');
    if (month.type !== 'month') return;
    form.classList.add('period-auto');
    month.addEventListener('change', () => {
        if (month.value && month.checkValidity()) form.requestSubmit();
    });
});

document.querySelectorAll('canvas[data-flow]').forEach(canvas => {
    const points = JSON.parse(canvas.dataset.flow);
    const output = canvas.nextElementSibling;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const money = new Intl.NumberFormat('es-MX', {
        style: 'currency', currency: canvas.dataset.currency, currencyDisplay: 'narrowSymbol',
    });
    const axisNumber = new Intl.NumberFormat('es-MX', { notation: 'compact', maximumFractionDigits: 1 });
    let graph;
    let selected = -1;

    const render = () => {
        const width = canvas.clientWidth;
        const height = canvas.clientHeight;
        if (!width || !height) return;
        const dpr = window.devicePixelRatio || 1;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        ctx.scale(dpr, dpr);
        const colors = getComputedStyle(canvas);
        const color = name => colors.getPropertyValue(name).trim();
        const left = 48;
        const right = width - 15;
        const top = 12;
        const bottom = height - 28;
        const maximum = Math.max(points.at(-1).income, points.at(-1).expense) / 100;
        const order = 10 ** Math.floor(Math.log10(maximum || 1));
        const maximumAxis = Math.ceil(maximum / order) * order || 1;
        const x = index => left + index * (right - left) / (points.length - 1);
        const y = cents => bottom - (cents / 100 / maximumAxis) * (bottom - top);
        graph = { x, left, right };

        ctx.font = '12px "Segoe UI", sans-serif';
        ctx.textBaseline = 'middle';
        ctx.textAlign = 'right';
        for (let i = 0; i <= 4; i++) {
            const value = maximumAxis * i / 4;
            const vertical = y(value * 100);
            ctx.strokeStyle = color('--line');
            ctx.beginPath();
            ctx.moveTo(left, vertical);
            ctx.lineTo(right, vertical);
            ctx.stroke();
            ctx.fillStyle = color('--muted');
            ctx.fillText('$' + axisNumber.format(value), left - 7, vertical);
        }

        ctx.textAlign = 'center';
        const labels = width < 365 ? [0, 9, 19, points.length - 1] : [0, 4, 9, 14, 19, 24, points.length - 1];
        labels.forEach(index => ctx.fillText(points[index].day, x(index), height - 10));

        const drawSeries = (key, stroke, fill) => {
            ctx.beginPath();
            points.forEach((point, index) => {
                if (index === 0) ctx.moveTo(x(index), y(point[key]));
                else ctx.lineTo(x(index), y(point[key]));
            });
            if (fill) {
                ctx.lineTo(right, bottom);
                ctx.lineTo(left, bottom);
                ctx.closePath();
                ctx.fillStyle = fill;
                ctx.fill();
                return;
            }
            ctx.lineWidth = 2.5;
            ctx.lineJoin = 'round';
            ctx.strokeStyle = stroke;
            ctx.stroke();
        };

        drawSeries('income', '', color('--chart-fill'));
        drawSeries('income', color('--chart-income'));
        drawSeries('expense', color('--chart-expense'));

        if (selected >= 0) {
            const horizontal = x(selected);
            ctx.lineWidth = 1;
            ctx.strokeStyle = color('--muted');
            ctx.setLineDash([3, 4]);
            ctx.beginPath();
            ctx.moveTo(horizontal, top);
            ctx.lineTo(horizontal, bottom);
            ctx.stroke();
            ctx.setLineDash([]);
            output.style.left = Math.max(7, Math.min(horizontal + 10, width - output.offsetWidth - 7)) + 'px';
        }
    };

    const show = index => {
        selected = Math.max(0, Math.min(points.length - 1, index));
        const point = points[selected];
        output.textContent = 'Día ' + point.day + ' · Ingresos ' + money.format(point.income / 100) +
            ' · Gastos ' + money.format(point.expense / 100);
        output.hidden = false;
        render();
    };

    const hide = () => {
        selected = -1;
        output.hidden = true;
        render();
    };

    canvas.addEventListener('pointermove', event => {
        if (!graph) return;
        const offset = event.clientX - canvas.getBoundingClientRect().left;
        const next = Math.round((offset - graph.left) * (points.length - 1) / (graph.right - graph.left));
        if (next !== selected) show(next);
    });
    canvas.addEventListener('pointerleave', hide);
    canvas.addEventListener('blur', hide);
    canvas.addEventListener('focus', () => show(points.length - 1));
    canvas.addEventListener('keydown', event => {
        if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
        event.preventDefault();
        const direction = { ArrowLeft: selected - 1, ArrowRight: selected + 1, Home: 0, End: points.length - 1 };
        show(direction[event.key]);
    });

    if ('ResizeObserver' in window) new ResizeObserver(render).observe(canvas);
    else window.addEventListener('resize', render);
    render();
});
