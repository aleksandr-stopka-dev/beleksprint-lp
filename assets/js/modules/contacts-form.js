import { markStart, sendForm } from '../core/send-form.js';

const showError = (field, text) => {
    const input = field.querySelector('input, textarea');
    const error = field.querySelector('.field__error');
    input.setAttribute('aria-invalid', 'true');
    error.textContent = text;
    error.hidden = false;
    field.classList.add('field--error');
};

const clearError = (field) => {
    const input = field.querySelector('input, textarea');
    const error = field.querySelector('.field__error');
    input.removeAttribute('aria-invalid');
    error.hidden = true;
    field.classList.remove('field--error');
};

const initContactsForm = (form) => {
    const typeButtons = [...form.querySelectorAll('[data-form-type]')];
    const question = form.querySelector('[data-form-question]');
    const submit = form.querySelector('[data-form-submit]');
    const consent = form.querySelector('input[name="consent"]');
    const consentLabel = consent.closest('.check');
    const consentError = form.querySelector('[data-form-error]');
    const status = form.querySelector('[data-form-status]');
    const typeInput = form.elements.type;
    markStart(form, form.elements.started);
    const labels = { question: 'Отправить вопрос', call: 'Жду звонка' };
    const requiredFields = [...form.querySelectorAll('.field:has(input[required])')];

    const selectType = (type) => {
        typeButtons.forEach((button) => {
            button.setAttribute('aria-pressed', String(button.dataset.formType === type));
        });
        typeInput.value = type;
        question.hidden = type !== 'question';
        submit.textContent = labels[type];
        status.hidden = true;
    };

    form.addEventListener('click', (event) => {
        const button = event.target.closest('[data-form-type]');
        if (button) selectType(button.dataset.formType);
    });

    consent.addEventListener('change', () => {
        consentLabel.classList.remove('check--error');
        consentError.hidden = true;
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        status.hidden = true;

        let valid = true;
        requiredFields.forEach((field) => {
            const input = field.querySelector('input');
            clearError(field);
            if (!input.value.trim()) {
                showError(field, 'Заполните поле.');
                valid = false;
            }
        });

        consentLabel.classList.toggle('check--error', !consent.checked);
        consentError.hidden = consent.checked;
        if (!consent.checked) valid = false;

        if (!valid) return;

        submit.disabled = true;
        form.setAttribute('aria-busy', 'true');
        const result = await sendForm(form.getAttribute('action'), new FormData(form));
        submit.disabled = false;
        form.removeAttribute('aria-busy');

        if (result.ok) {
            form.reset();
            selectType('question');
        } else {
            Object.entries(result.errors).forEach(([name, text]) => {
                const field = form.elements[name]?.closest('.field');
                if (field) showError(field, text);
            });
        }
        status.textContent = result.message;
        status.classList.toggle('form__status--error', !result.ok);
        status.hidden = false;
    });
};

export default initContactsForm;
