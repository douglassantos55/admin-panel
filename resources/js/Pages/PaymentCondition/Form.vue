<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
    payment_condition: {
        type: Object,
        required: false,
    },
    payment_types: {
        type: Array,
        required: true,
    },
})

const { form, submit } = useForm(props.payment_condition || {
    name: '',
    title: '',
    increment: '',
    payment_type_id: '',
    installments: [],
})
</script>

<template>
    <h1 class="mb-4">
        {{ payment_condition ? 'Editar' : 'Cadastrar' }} Condição de Pagamento
    </h1>

    <form @submit.prevent="submit('payment_conditions')">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <Input
                    required
                    label="Nome"
                    v-model="form.name"
                    :error="form.errors.name"
                />
            </div>

            <div class="col-xs-12 col-sm-3">
                <Input
                    required
                    label="Titulo"
                    v-model="form.title"
                    :error="form.errors.title"
                />
            </div>

            <div class="col-xs-12 col-sm-3">
                <Select
                    required
                    label="Tipo pagamento"
                    v-model="form.payment_type_id"
                    :options="payment_types"
                    :error="form.errors.payment_type_id"
                    placeholder="Escolha o tipo de pagamento"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    label="Taxa (%)"
                    v-model="form.increment"
                    :error="form.errors.increment"
                />
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-xs-12 col-sm-3" v-for="(installment, i) in form.installments" :key="i">
                <Input
                    :label="`Qtd dias ${i+1}ª parcela`"
                    v-model="form.installments[i]"
                    :error="form.errors[`installments.${i}`]"
                />
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button
                type="button"
                class="btn btn-secondary"
                @click="form.installments.push('')"
            >
                Adicionar parcela
            </button>

            <button
                type="button"
                class="btn btn-danger"
                @click="form.installments.pop()"
                v-if="form.installments.length > 0"
            >
                Remover parcela
            </button>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                {{ payment_condition ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link :href="route('payment_conditions.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
