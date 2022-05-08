<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
    payment_types: {
        type: Array,
        required: true,
    },
})

const { form, submit } = useForm({
    name: '',
    title: '',
    increment: '',
    payment_type_id: '',
    installments: [],
})
</script>

<template>
    <h1 class="mb-4">
        Cadastrar Condição de Pagamento
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

            <div class="col-xs-12 col-sm-4">
                <Input
                    required
                    label="Titulo"
                    v-model="form.title"
                    :error="form.errors.title"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Select
                    required
                    label="Tipo pagamento"
                    v-model="form.payment_type_id"
                    :options="payment_types"
                    :error="form.errors.payment_type_id"
                    placeholder="Escolha o tipo de pagamento"
                />
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-xs-12 col-sm-3" v-for="(installment, i) in form.installments" :key="i">
                <Input
                    :label="`Qtd dias ${i+1} parcela`"
                    v-model="form.installments[i]"
                    :error="form.errors[`installments.${i}`]"
                />
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="button" class="btn btn-secondary" @click="form.installments.push('')">
                Adicionar parcela
            </button>

            <button type="button" class="btn btn-danger" @click="form.installments.pop()">
                Remover parcela
            </button>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                Cadastrar
            </button>

            <Link :href="route('payment_types.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
