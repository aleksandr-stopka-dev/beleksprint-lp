const initTabs = (node) => {
    const scope = node.closest('section') ?? document;
    const tabs = [...node.querySelectorAll('[role="tab"]')];
    const panels = [...scope.querySelectorAll('[data-tab-panel]')];
    if (!tabs.length) return;

    const selectTab = (tab, focus = false) => {
        const key = tab.dataset.tab;
        tabs.forEach((item) => {
            const active = item === tab;
            item.setAttribute('aria-selected', String(active));
            item.tabIndex = active ? 0 : -1;
        });
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.tabPanel !== key;
        });
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
};

export default initTabs;
