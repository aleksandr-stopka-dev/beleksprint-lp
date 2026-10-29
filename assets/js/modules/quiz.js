import { markStart, sendForm } from '../core/send-form.js';

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
            node.hidden = Number(node.dataset.quizStep) !== state.step;
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

    const goTo = (step) => {
        state.step = Math.min(Math.max(step, 0), lastStep);
        renderStep();
    };

    const selectOption = (kind, value) => {
        selection[kind] = value;
        renderOptions(kind);
        renderStep();
    };

    const applyChannel = (button) => {
        state.channel = button.dataset.quizChannel;
        channelButtons.forEach((item) => {
            item.setAttribute('aria-pressed', String(item === button));
        });
        contactLabel.textContent = button.dataset.fieldLabel;
        contactInput.placeholder = button.dataset.fieldPlaceholder;
    };

    const submit = async (button) => {
        error.hidden = true;
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
            goTo(lastStep);
            return;
        }
        error.textContent = result.errors.contact ?? result.message;
        error.hidden = false;
        if (result.errors.contact) contactInput.focus();
    };

    const reset = () => {
        selection.product = null;
        selection.quantity = null;
        selection.print = null;
        ['product', 'quantity', 'print'].forEach(renderOptions);
        if (consent) consent.checked = false;
        contactInput.value = '';
        error.hidden = true;
        goTo(0);
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
        goTo(1);
        quiz.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    renderStep();
};

export default initQuiz;
