import { useForm } from '@inertiajs/inertia-vue3'

export default function (initialData) {
    const form = useForm(initialData)

    function submit(entity) {
        form.clearErrors()
        const options = { preserveScroll: true, preserveState: true }

        if (form.id) {
            form.put(route(`${entity}.update`, form.id), options)
        } else {
            form.post(route(`${entity}.store`), options)
        }
    }

    return { form, submit }
}
