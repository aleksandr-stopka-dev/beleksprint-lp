export const initFab = () => {
    const fab = document.querySelector('[data-fab]');
    const toggle = fab?.querySelector('[data-fab-toggle]');
    if (!toggle) return;

    const isOpen = () => toggle.getAttribute('aria-expanded') === 'true';

    const setOpen = (open) => {
        toggle.setAttribute('aria-expanded', String(open));
        fab.classList.toggle('is-open', open);
    };

    toggle.addEventListener('click', () => setOpen(!isOpen()));

    fab.addEventListener('click', (event) => {
        if (event.target.closest('a')) setOpen(false);
    });

    document.addEventListener('click', (event) => {
        if (isOpen() && !fab.contains(event.target)) setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isOpen()) {
            setOpen(false);
            toggle.focus();
        }
    });
};
