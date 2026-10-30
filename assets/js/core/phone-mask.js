const MAX_RU = 11;
const MAX_BY = 12;

const toDigits = (value) => {
    const digits = value.replace(/\D/g, '');
    if (digits.startsWith('375')) return digits.slice(0, MAX_BY);
    if (digits.startsWith('8')) return `7${digits.slice(1, MAX_RU)}`;
    if (digits.startsWith('9')) return `7${digits}`.slice(0, MAX_RU);
    return digits.slice(0, MAX_RU);
};

const format = (digits) => {
    if (!digits) return '';
    const by = digits.startsWith('375');
    const code = by ? digits.slice(0, 3) : digits.slice(0, 1);
    const rest = digits.slice(code.length);
    const groups = by ? [2, 3, 2, 2] : [3, 3, 2, 2];
    const parts = [];
    let offset = 0;
    groups.forEach((size) => {
        const part = rest.slice(offset, offset + size);
        if (part) parts.push(part);
        offset += size;
    });
    const [area = '', ...tail] = parts;
    let result = `+${code}`;
    if (area) result += ` (${area}${area.length === groups[0] ? ')' : ''}`;
    if (tail.length) result += ` ${tail.join('-')}`;
    return result;
};

export const attachPhoneMask = (input, isActive = () => true) => {
    let previous = '';

    const apply = (event) => {
        if (!isActive(input.value)) {
            previous = '';
            return;
        }
        let digits = toDigits(input.value);
        const deleting = event?.inputType?.startsWith('delete');
        if (deleting && digits === previous) digits = digits.slice(0, -1);
        previous = digits;
        input.value = format(digits);
    };

    input.addEventListener('input', apply);
    return () => apply();
};
