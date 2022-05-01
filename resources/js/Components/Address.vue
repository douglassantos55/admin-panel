<script setup>
defineProps({
    address: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <template v-if="address.postcode">
        <span class="d-block">{{ address.street }}, {{ address.number }}, {{ address.neighborhood }}</span>
        <span class="d-block">{{ address.city }}, {{ address.state }} - {{ address.postcode }}</span>
    </template>
</template>
