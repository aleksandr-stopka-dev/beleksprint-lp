const LEAVE = 200;
const RESIZE = 450;

const initTabs = (node) => {
    const scope = node.closest('section') ?? document;
    const tabs = [...node.querySelectorAll('[role="tab"]')];
    const panels = [...scope.querySelectorAll('[data-tab-panel]')];
    if (!tabs.length) return;

    const stage = panels[0].parentElement;
    let timers = [];

    const reset = () => {
        timers.forEach(clearTimeout);
        timers = [];
        stage.classList.remove('is-animating');
        stage.style.height = '';
        panels.forEach((panel) => panel.classList.remove('is-leaving', 'is-entering'));
    };

    const showPanel = (key) => {
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.tabPanel !== key;
        });
    };

    const swapPanel = (key) => {
        reset();
        const current = panels.find((panel) => !panel.hidden);
        const next = panels.find((panel) => panel.dataset.tabPanel === key);
        if (!current || !next || current === next) {
            showPanel(key);
            return;
        }

        stage.style.height = `${stage.offsetHeight}px`;
        stage.classList.add('is-animating');
        current.classList.add('is-leaving');

        timers.push(setTimeout(() => {
            const from = stage.offsetHeight;
            current.hidden = true;
            current.classList.remove('is-leaving');
            next.hidden = false;
            stage.style.height = '';
            const to = stage.offsetHeight;
            stage.style.height = `${from}px`;
            stage.getBoundingClientRect();
            next.classList.add('is-entering');
            stage.style.height = `${to}px`;
            timers.push(setTimeout(reset, RESIZE));
        }, LEAVE));
    };

    const moveThumb = () => {
        const active = tabs.find((tab) => tab.getAttribute('aria-selected') === 'true');
        if (!active) return;
        node.style.setProperty('--thumb-x', `${active.offsetLeft}px`);
        node.style.setProperty('--thumb-y', `${active.offsetTop}px`);
        node.style.setProperty('--thumb-w', `${active.offsetWidth}px`);
        node.style.setProperty('--thumb-h', `${active.offsetHeight}px`);
    };

    const selectTab = (tab, focus = false) => {
        const key = tab.dataset.tab;
        tabs.forEach((item) => {
            const active = item === tab;
            item.setAttribute('aria-selected', String(active));
            item.tabIndex = active ? 0 : -1;
        });
        moveThumb();
        swapPanel(key);
        if (focus) tab.focus();
    };

    const moveFocus = (event) => {
        const current = tabs.indexOf(event.target);
        if (current === -1) return;
        const keys = { ArrowRight: 1, ArrowLeft: -1 };
        if (event.key in keys) {
            event.preventDefault();
            const next = (current + keys[event.key] + tabs.length) % tabs.length;
            selectTab(tabs[next], true);
        }
        if (event.key === 'Home') {
            event.preventDefault();
            selectTab(tabs[0], true);
        }
        if (event.key === 'End') {
            event.preventDefault();
            selectTab(tabs[tabs.length - 1], true);
        }
    };

    node.addEventListener('click', (event) => {
        const tab = event.target.closest('[role="tab"]');
        if (tab && node.contains(tab)) selectTab(tab);
    });
    node.addEventListener('keydown', moveFocus);

    moveThumb();
    node.classList.add('is-ready');
    setTimeout(() => node.classList.add('is-animated'), 50);
    new ResizeObserver(moveThumb).observe(node);
};

export default initTabs;
