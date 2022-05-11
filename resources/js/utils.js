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

const formatter = new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 })

export function format(num) {
    return formatter.format(num)
}
