import { initHeader } from './modules/header.js';
import { initFab } from './modules/fab.js';
import initQuiz from './modules/quiz.js';
import { lazyInit } from './core/lazy-init.js';

export { loadScript, loadStyle } from './core/loaders.js';

initHeader();
initFab();
initQuiz();

lazyInit('[data-tabs]', () => import('./modules/tabs.js'));
lazyInit('.hs', () => import('./modules/scroll-regions.js'));
lazyInit('[data-reviews]', () => import('./modules/reviews.js'));
lazyInit('[data-form]', () => import('./modules/contacts-form.js'));
