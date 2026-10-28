export const lazyInit = (selector, loader, rootMargin = '200px') => {
    const nodes = document.querySelectorAll(selector);
    if (!nodes.length) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(({ isIntersecting, target }) => {
            if (!isIntersecting) return;
            obs.unobserve(target);
            loader().then(({ default: init }) => init(target));
        });
    }, { rootMargin });

    nodes.forEach((node) => observer.observe(node));
};
