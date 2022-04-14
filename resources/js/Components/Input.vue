<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">{{ label }}</label>
        <input
            :id="id"
            v-bind="$attrs"
            class="form-control"
            :class="{ 'is-invalid': !!error }"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div class="invalid-feedback" v-if="error">{{ error }}</div>
    </div>
</template>

<script>
export default {
    inheritAttrs: false,
    emits: [
        'update:modelValue',
    ],
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        error: {
            type: Array,
            required: false,
        },
        label: {
            type: String,
            required: false,
        },
    },
    computed: {
        id() {
            return `${this.label.toLowerCase()}_${parseInt(Math.random() * 100)}`;
        }
    },
}
</script>
