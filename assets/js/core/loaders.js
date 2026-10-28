const cache = new Map();

const load = (path, create) => {
    const url = new URL(path, import.meta.url).href;
    if (!cache.has(url)) {
        cache.set(url, new Promise((resolve, reject) => {
            const el = create(url);
            el.onload = resolve;
            el.onerror = reject;
            document.head.append(el);
        }));
    }
    return cache.get(url);
};

export const loadScript = (path) => load(path, (url) => {
    const script = document.createElement('script');
    script.src = url;
    script.async = true;
    return script;
});

export const loadStyle = (path) => load(path, (url) => {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = url;
    return link;
});
