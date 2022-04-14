<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">{{ label }}</label>
        <select
            :id="id"
            v-bind="$attrs"
            class="form-select"
            :class="{ 'is-invalid': !!error }"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option
                v-for="option in options"
                :key="option[keyBy]"
                :value="option[keyBy]"
            >
                {{ option[textBy] }}
            </option>
        </select>

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
        options: {
            type: Array,
            required: true,
        },
        error: {
            type: String,
            required: false,
        },
        keyBy: {
            type: String,
            default: 'id',
        },
        textBy: {
            type: String,
            default: 'name',
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
