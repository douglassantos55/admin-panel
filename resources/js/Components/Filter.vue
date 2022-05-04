<script setup>
import { computed } from 'vue'
import { usePage, Link, useForm } from '@inertiajs/inertia-vue3'

const props = defineProps({
    filters: Object,
})

const form = useForm(props.filters)

const url = computed(() => {
    const { url } = usePage()
    return url.value.split('?')[0]
})

const filtered = computed(() => {
    for (const key in props.filters) {
        if (form[key] !== '') {
            return true
        }
    }
    return false
})

function filter() {
    form.get(url.value, { preserveState: true })
}
</script>

<template>
    <form @submit.prevent="filter">
        <div class="row">
            <slot :filters="form" />

            <slot name="actions" :filtered="filtered">
                <div class="col-xs-12 col-sm-3">
                    <label class="d-block mb-2">&nbsp;</label>
                    <button type="submit" class="btn btn-secondary">Filtrar</button>

                    <Link v-if="filtered" class="btn btn-link" :href="url">
                        Limpar filtros
                    </Link>
                </div>
            </slot>
        </div>
    </form>
</template>
