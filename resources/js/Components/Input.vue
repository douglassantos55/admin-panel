<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">
            {{ label }}
            <span class="text-danger" v-if="$attrs.required != undefined">*</span>
        </label>

        <component :is="component"
            :id="id"
            ref="input"
            :rows="rows"
            v-bind="$attrs"
            :value="modelValue"
            :class="['form-control', { 'is-invalid': !!error }]"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div class="invalid-feedback" v-if="error">{{ error }}</div>
    </div>
</template>

<script>
import { ref } from 'vue'
import useId from '../Composables/useId'
import { useCurrencyInput } from 'vue-currency-input'

export default {
    expose: ['focus'],
    inheritAttrs: false,
    emits: ['change', 'update:modelValue'],
    props: {
        modelValue: {
            type: [String, Number],
            required: false,
        },
        error: {
            type: String,
            required: false,
        },
        label: {
            type: String,
            required: false,
        },
        rows: {
            type: String,
            required: false,
        },
        decimal: {
            type: Boolean,
            required: false,
        },
        currency: {
            type: Boolean,
            required: false,
        },
    },
    setup(props) {
        let input = ref(null)
        const id = useId(props.label)

        if (props.decimal || props.currency) {
            const { inputRef } = useCurrencyInput({
                locale: 'pt-BR',
                currency: 'BRL',
                useGrouping: true,
                precision: 2,
                currencyDisplay: 'symbol',
            })
            input = inputRef
        }

        function focus() {
            input && input.value.focus()
        }

        const component = props.rows ? 'textarea' : 'input'

        return { id, input, focus, component }
    },
}
</script>
