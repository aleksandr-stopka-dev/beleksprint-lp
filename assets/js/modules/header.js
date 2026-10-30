const getNavLinks = (nav) => [...nav.querySelectorAll('a[href]')];

const initSticky = (bar) => {
    const root = document.documentElement;
    let offset = 0;
    let frame = 0;

    const measure = () => {
        offset = parseFloat(getComputedStyle(bar).top) || 0;
        root.style.setProperty('--header-h', `${Math.max(bar.offsetHeight + offset, 0)}px`);
    };

    const update = () => {
        frame = 0;
        const stuck = window.scrollY > 0 && bar.getBoundingClientRect().top <= offset + 0.5;
        bar.classList.toggle('is-stuck', stuck);
    };

    const onScroll = () => {
        if (!frame) frame = requestAnimationFrame(update);
    };

    measure();
    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', () => {
        measure();
        onScroll();
    });
    new ResizeObserver(measure).observe(bar);
};

export const initHeader = () => {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    const bar = header.querySelector('.header__top');
    if (bar) initSticky(bar);

    const toggle = header.querySelector('[data-nav-toggle]');
    const nav = header.querySelector('[data-nav]');
    if (!toggle || !nav) return;

    const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

    const setMenu = (open) => {
        if (open && bar) {
            document.documentElement.style.setProperty('--nav-top', `${Math.max(bar.getBoundingClientRect().bottom, 0)}px`);
        }
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
        header.classList.toggle('is-open', open);
        document.body.classList.toggle('is-locked', open);
    };

    const closeMenu = () => {
        if (!isOpen()) return;
        setMenu(false);
        toggle.focus();
    };

    const toggleMenu = () => {
        const open = !isOpen();
        setMenu(open);
        if (open) getNavLinks(nav)[0]?.focus();
    };

    const trapFocus = (event) => {
        const items = [toggle, ...getNavLinks(nav)];
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    };

    const onKeydown = (event) => {
        if (!isOpen()) return;
        if (event.key === 'Escape') closeMenu();
        if (event.key === 'Tab') trapFocus(event);
    };

    const onNavClick = (event) => {
        if (event.target.closest('a')) closeMenu();
    };

    const desktop = window.matchMedia('(min-width: 1201px)');
    const onBreakpoint = (event) => {
        if (event.matches) setMenu(false);
    };

    toggle.addEventListener('click', toggleMenu);
    nav.addEventListener('click', onNavClick);
    document.addEventListener('keydown', onKeydown);
    desktop.addEventListener('change', onBreakpoint);
};
