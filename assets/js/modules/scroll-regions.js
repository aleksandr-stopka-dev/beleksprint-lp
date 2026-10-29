const initScrollRegion = (node) => {
    const labelId = node.closest('section')?.getAttribute('aria-labelledby');
    if (labelId && !node.hasAttribute('aria-labelledby')) {
        node.setAttribute('aria-labelledby', labelId);
    }

    const update = () => {
        if (node.scrollWidth > node.clientWidth) {
            node.tabIndex = 0;
        } else {
            node.removeAttribute('tabindex');
        }
    };

    new ResizeObserver(update).observe(node);
    update();
};

export default initScrollRegion;
