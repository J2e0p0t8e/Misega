/**
 * Formats a numeric price into FCFA currency string with space thousand separators.
 * Example: 5000 -> "5 000 FCFA", 12500 -> "12 500 FCFA"
 *
 * @param {number|string} amount
 * @param {boolean} includeCurrency - whether to append " FCFA" (default: true)
 * @returns {string}
 */
export function formatPrice(amount, includeCurrency = true) {
    if (amount === null || amount === undefined || isNaN(amount)) {
        return includeCurrency ? '0 FCFA' : '0';
    }
    const num = Math.round(Number(amount));
    // Format with space as thousand separator (standard French / FCFA convention)
    const formatted = num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    return includeCurrency ? `${formatted} FCFA` : formatted;
}

export default formatPrice;
