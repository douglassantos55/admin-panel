export function formatAddress(address) {
    return [
        address.postcode,
        address.neighborhood,
        address.city,
        address.state,
    ].filter(value => value).join(', ')
}
