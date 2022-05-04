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
