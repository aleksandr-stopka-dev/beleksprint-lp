const initLightbox = (node) => {
    const box = document.querySelector('[data-lightbox]');
    if (!box) return;

    const stage = box.querySelector('[data-lightbox-stage]');
    const img = box.querySelector('[data-lightbox-img]');
    let opener = null;

    const open = (button) => {
        opener = button;
        img.src = button.dataset.reviewZoom;
        img.alt = button.dataset.reviewAlt || '';
        box.classList.remove('is-zoomed');
        stage.scrollTo(0, 0);
        document.body.classList.add('is-locked');
        box.showModal();
    };

    node.addEventListener('click', (event) => {
        const button = event.target.closest('[data-review-zoom]');
        if (button) open(button);
    });

    box.addEventListener('click', (event) => {
        if (event.target.closest('[data-lightbox-close]')) {
            box.close();
        } else if (event.target === img) {
            box.classList.toggle('is-zoomed');
            stage.scrollTo(0, 0);
        } else if (event.target === box || event.target === stage) {
            box.close();
        }
    });

    box.addEventListener('close', () => {
        document.body.classList.remove('is-locked');
        img.removeAttribute('src');
        opener?.focus();
    });
};

const initReviews = (node) => {
    initLightbox(node);

    const thumbs = [...node.querySelectorAll('[data-review-thumb]')];
    const panels = [...node.querySelectorAll('[data-review-panel]')];
    if (!thumbs.length) return;

    const selectReview = (index) => {
        panels.forEach((panel) => {
            const order = Number(panel.dataset.reviewPanel);
            panel.classList.toggle('is-active', panel.dataset.reviewPanel === index);
            panel.classList.toggle('is-before', order < Number(index));
            panel.inert = panel.dataset.reviewPanel !== index;
        });
        thumbs.forEach((thumb) => {
            thumb.setAttribute('aria-pressed', String(thumb.dataset.reviewThumb === index));
        });
    };

    node.addEventListener('click', (event) => {
        const thumb = event.target.closest('[data-review-thumb]');
        if (thumb) selectReview(thumb.dataset.reviewThumb);
    });
};

export default initReviews;
