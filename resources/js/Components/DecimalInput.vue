<template>
    <div class="mb-3" :class="$attrs.class">
        <label :for="id" class="form-label" v-if="label">
            {{ label }}
            <span class="text-danger" v-if="$attrs.required != undefined">*</span>
        </label>

        <input
            :id="id"
            ref="inputRef"
            v-bind="$attrs"
            :class="['form-control', { 'is-invalid': !!error }]"
        />
        <div class="invalid-feedback" v-if="error">{{ error }}</div>
    </div>
</template>

<script>
import { watch } from 'vue'
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
        currency: {
            type: Boolean,
            required: false,
        },
    },
    setup(props) {
        const id = useId(props.label)

        const options = {
            locale: "pt-BR",
            useGrouping: true,
            precision: 2,
            currency: "BRL",
            currencyDisplay: "hidden",
            hideGroupingSeparatorOnFocus: false,
        }

        if (props.currency) {
            options.currencyDisplay = "symbol"
            options.hideCurrencySymbolOnFocus = false
        }

        const { inputRef, setValue } = useCurrencyInput(options)

        watch(() => props.modelValue, value => setValue(value))

        return { id, inputRef }
    },
}
</script>
