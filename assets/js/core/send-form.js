export const markStart = (node, input) => {
    node.addEventListener('focusin', () => {
        if (!input.value) input.value = Math.floor(Date.now() / 1000);
    }, { once: true });
};

export const sendForm = async (endpoint, data) => {
    try {
        const response = await fetch(endpoint, { method: 'POST', body: data });
        const result = await response.json();
        return result.success
            ? { ok: true, message: result.data.message }
            : { ok: false, message: result.data?.message ?? 'Не удалось отправить. Позвоните нам.', errors: result.data?.errors ?? {} };
    } catch {
        return { ok: false, message: 'Ошибка сети. Попробуйте ещё раз.', errors: {} };
    }
};
