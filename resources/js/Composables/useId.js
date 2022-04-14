export default function (label) {
    const randomId = parseInt(Math.random() * 100)
    label = label.replace(/[^a-zA-Z]+/g, '_').toLowerCase()

    return `${label}_${randomId}`;
}
