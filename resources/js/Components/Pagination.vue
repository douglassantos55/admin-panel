<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
    links: {
        type: Array,
        required: true,
    },
    lastPage: {
        type: Number,
        required: true,
    },
})
</script>

<template>
    <nav v-if="lastPage > 1">
        <ul class="pagination justify-content-center">
            <li :class="['page-item', { disabled: !link.url, active: link.active }]" v-for="(link, i) in links" :key="i">
                <Link :href="link.url" class="page-link" v-html="link.label" preserve-state />
            </li>
        </ul>
    </nav>
</template>
