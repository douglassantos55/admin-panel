<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">
            {{ label }}
            <span class="text-danger" v-if="$attrs.required != undefined">*</span>
        </label>

        <select
            :id="id"
            v-bind="$attrs"
            :value="modelValue"
            :class="['form-select', { 'is-invalid': !!error }]"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option value="" v-if="placeholder">{{ placeholder }}</option>

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
            type: [String, Number],
            required: false,
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
        placeholder: {
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
