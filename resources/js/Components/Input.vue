<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">{{ label }}</label>
        <input
            :id="id"
            ref="input"
            v-bind="$attrs"
            :value="modelValue"
            class="form-control"
            :class="{ 'is-invalid': !!error }"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div class="invalid-feedback" v-if="error">{{ error }}</div>
    </div>
</template>

<script>
import { ref } from 'vue'
import useId from '../Composables/useId'

export default {
    expose: ['focus'],
    inheritAttrs: false,
    emits: ['update:modelValue'],
    props: {
        modelValue: {
            type: String,
            required: true,
        },
        error: {
            type: String,
            required: false,
        },
        label: {
            type: String,
            required: false,
        },
    },
    setup(props, context) {
        const input = ref(null)
        const id = useId(props.label)

        function focus() {
            input && input.value.focus()
        }

        return { id, input, focus }
    },
}
</script>
