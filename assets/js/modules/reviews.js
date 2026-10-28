const initReviews = (node) => {
    const thumbs = [...node.querySelectorAll('[data-review-thumb]')];
    const panels = [...node.querySelectorAll('[data-review-panel]')];
    if (!thumbs.length) return;

    const selectReview = (index) => {
        panels.forEach((panel) => {
            panel.hidden = panel.dataset.reviewPanel !== index;
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
