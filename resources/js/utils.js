export const UNITS = {
    pç: "Peça",
    "m/l": "Metro",
}

export const units = asOptions(UNITS)

function asOptions(object) {
    return Object.keys(object).map(key => ({
        id: key,
        name: object[key],
    }))
}

export function format(num) {
    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
    }).format(num)
}

export function formatCurrency(num) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(num)
}

export function formatDate(date) {
    return new Intl.DateTimeFormat('pt-BR').format(new Date(date))
}

export function addDays(date, days) {
    const dateObj = new Date(date)
    dateObj.setDate(dateObj.getDate() + parseInt(days))

    return [
        dateObj.getUTCFullYear(),
        (dateObj.getUTCMonth()+1).toString().padStart(2, '0'),
        dateObj.getUTCDate().toString().padStart(2, '0')
    ].join('-')
}
