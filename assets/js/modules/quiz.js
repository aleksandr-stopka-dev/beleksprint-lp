import { attachPhoneMask } from '../core/phone-mask.js';
import { markStart, sendForm } from '../core/send-form.js';

const ADVANCE_DELAY = 320;

const initQuiz = () => {
    const quiz = document.querySelector('[data-quiz]');
    if (!quiz) return;

    const steps = [...quiz.querySelectorAll('[data-quiz-step]')];
    const bars = [...quiz.querySelectorAll('[data-quiz-bar]')];
    const consent = quiz.querySelector('input[name="quiz_consent"]');
    const channelButtons = [...quiz.querySelectorAll('[data-quiz-channel]')];
    const contactLabel = quiz.querySelector('[data-quiz-field-label]');
    const contactInput = quiz.querySelector('[data-quiz-contact]');
    const summary = {
        product: quiz.querySelector('[data-quiz-summary="product"]'),
        quantity: quiz.querySelector('[data-quiz-summary="quantity"]'),
        print: quiz.querySelector('[data-quiz-summary="print"]'),
    };
    const selection = { product: null, quantity: null, print: null };
    const state = { step: 0, channel: channelButtons.find((b) => b.getAttribute('aria-pressed') === 'true')?.dataset.quizChannel ?? '' };

    const trap = quiz.querySelector('[data-quiz-trap]');
    const error = quiz.querySelector('[data-quiz-error]');
    const started = { value: '' };
    markStart(quiz, started);

    const lastStep = steps.length - 1;
    const choiceStep = { product: 0, quantity: 1, print: 2 };
    let advanceTimer = 0;

    const isStepReady = (step) => {
        if (step === 0) return selection.product !== null;
        if (step === 1) return selection.quantity !== null;
        if (step === 2) return selection.print !== null;
        if (step === 3) return Boolean(consent?.checked);
        return true;
    };

    const labelOf = (kind) => {
        const option = quiz.querySelector(`[data-quiz-option="${kind}"][data-value="${selection[kind]}"]`);
        return option ? option.dataset.label : '—';
    };

    const renderSummary = () => {
        summary.product.textContent = labelOf('product');
        summary.quantity.textContent = labelOf('quantity');
        summary.print.textContent = labelOf('print');
    };

    const renderOptions = (kind) => {
        quiz.querySelectorAll(`[data-quiz-option="${kind}"]`).forEach((option) => {
            option.setAttribute('aria-pressed', String(option.dataset.value === selection[kind]));
        });
    };

    const renderStep = () => {
        steps.forEach((node) => {
            const index = Number(node.dataset.quizStep);
            node.classList.toggle('is-active', index === state.step);
            node.classList.toggle('is-before', index < state.step);
            node.inert = index !== state.step;
        });
        bars.forEach((bar) => {
            const index = Number(bar.dataset.quizBar);
            bar.classList.toggle('is-done', index < state.step);
            bar.classList.toggle('is-current', index === state.step);
        });
        quiz.querySelectorAll('[data-quiz-next]').forEach((button) => {
            const owner = button.closest('[data-quiz-step]');
            button.disabled = !isStepReady(Number(owner.dataset.quizStep));
        });
        renderSummary();
    };

    const goTo = (step, focus = true) => {
        clearTimeout(advanceTimer);
        state.step = Math.min(Math.max(step, 0), lastStep);
        renderStep();
        if (focus) steps[state.step].querySelector('.quiz__title')?.focus({ preventScroll: true });
    };

    const selectOption = (kind, value) => {
        selection[kind] = value;
        renderOptions(kind);
        renderStep();
        clearTimeout(advanceTimer);
        advanceTimer = setTimeout(() => {
            if (state.step === choiceStep[kind]) goTo(state.step + 1);
        }, ADVANCE_DELAY);
    };

    const contactField = contactInput.closest('.field');

    const setContactInvalid = (invalid) => {
        contactField.classList.toggle('field--error', invalid);
        if (invalid) contactInput.setAttribute('aria-invalid', 'true');
        else contactInput.removeAttribute('aria-invalid');
    };

    contactInput.addEventListener('input', () => setContactInvalid(false));

    const refreshMask = attachPhoneMask(contactInput, (value) => state.channel === 'wa' || (state.channel === 'tg' && /^[\s+\d(]/.test(value)));

    const channelsBox = channelButtons[0]?.parentElement;

    const moveChannelThumb = () => {
        const active = channelButtons.find((item) => item.getAttribute('aria-pressed') === 'true');
        if (!channelsBox || !active || !active.offsetWidth) return;
        channelsBox.style.setProperty('--thumb-x', `${active.offsetLeft}px`);
        channelsBox.style.setProperty('--thumb-y', `${active.offsetTop}px`);
        channelsBox.style.setProperty('--thumb-w', `${active.offsetWidth}px`);
        channelsBox.style.setProperty('--thumb-h', `${active.offsetHeight}px`);
        channelsBox.classList.add('is-ready');
    };

    const applyChannel = (button) => {
        state.channel = button.dataset.quizChannel;
        channelButtons.forEach((item) => {
            item.setAttribute('aria-pressed', String(item === button));
        });
        contactLabel.textContent = button.dataset.fieldLabel;
        contactInput.placeholder = button.dataset.fieldPlaceholder;
        contactInput.inputMode = state.channel === 'mail' ? 'email' : state.channel === 'wa' ? 'tel' : 'text';
        refreshMask();
        moveChannelThumb();
    };

    if (channelsBox) {
        moveChannelThumb();
        setTimeout(() => channelsBox.classList.add('is-animated'), 50);
        new ResizeObserver(moveChannelThumb).observe(channelsBox);
    }

    const submit = async (button) => {
        error.hidden = true;
        setContactInvalid(false);
        button.disabled = true;
        quiz.setAttribute('aria-busy', 'true');

        const data = new FormData();
        data.append('action', 'theme_form');
        data.append('form_id', 'quiz');
        data.append('started', started.value);
        data.append('website', trap.value);
        data.append('product', labelOf('product'));
        data.append('quantity', labelOf('quantity'));
        data.append('print', labelOf('print'));
        data.append('channel', state.channel);
        data.append('contact', contactInput.value.trim());
        data.append('consent', consent.checked ? '1' : '');

        const result = await sendForm(quiz.dataset.endpoint, data);
        quiz.removeAttribute('aria-busy');
        button.disabled = false;

        if (result.ok) {
            goTo(lastStep, false);
            return;
        }
        if (result.errors.contact) {
            setContactInvalid(true);
            contactInput.focus();
            return;
        }
        error.textContent = result.message;
        error.hidden = false;
    };

    const reset = () => {
        selection.product = null;
        selection.quantity = null;
        selection.print = null;
        ['product', 'quantity', 'print'].forEach(renderOptions);
        if (consent) consent.checked = false;
        contactInput.value = '';
        error.hidden = true;
        setContactInvalid(false);
        goTo(0, false);
    };

    quiz.addEventListener('click', (event) => {
        const option = event.target.closest('[data-quiz-option]');
        if (option) {
            selectOption(option.dataset.quizOption, option.dataset.value);
            return;
        }

        const channel = event.target.closest('[data-quiz-channel]');
        if (channel) {
            applyChannel(channel);
            return;
        }

        if (event.target.closest('[data-quiz-back]')) {
            goTo(state.step - 1);
            return;
        }

        if (event.target.closest('[data-quiz-next]')) {
            if (!isStepReady(state.step)) return;
            if (state.step === lastStep - 1) {
                submit(event.target.closest('[data-quiz-next]'));
                return;
            }
            goTo(state.step + 1);
            return;
        }

        if (event.target.closest('[data-quiz-restart]')) {
            reset();
        }
    });

    consent?.addEventListener('change', renderStep);

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-quiz-product]');
        if (!trigger) return;
        const product = quiz.querySelector(`[data-quiz-option="product"][data-value="${trigger.dataset.quizProduct}"]`);
        if (!product) return;
        selection.product = product.dataset.value;
        renderOptions('product');
        goTo(1, false);
        quiz.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    renderStep();
};

export default initQuiz;
