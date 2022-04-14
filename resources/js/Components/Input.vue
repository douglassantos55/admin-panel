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
import useId from '../Composables/useId'

export default {
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
    setup(props) {
        const id = useId(props.label)
        return { id }
    },
}
</script>
