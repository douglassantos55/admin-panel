<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import useForm from '../../Composables/useForm'
import AddressForm from '../../Components/AddressForm.vue'

const props = defineProps({
    supplier: {
        type: Object,
        required: false,
    },
})

const { form, submit } = useForm(props.supplier || {
    social_name: '',
    legal_name: '',
    cnpj: '',
    email: '',
    insc_est: '',
    phone: '',
    address: {
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
        postcode: '',
    },
    observations: '',
})
</script>

<template>
    <h1 class="mb-4">
        {{ supplier ? 'Editar' : 'Cadastrar' }} fornecedor
    </h1>

    <form @submit.prevent="submit('suppliers')">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <Input
                    required
                    label="Nome Fantasia"
                    v-model="form.social_name"
                    :error="form.errors.social_name"
                />
            </div>

            <div class="col-xs-12 col-sm-5">
                <Input
                    label="Razão Social"
                    v-model="form.legal_name"
                    :error="form.errors.legal_name"
                />
            </div>

            <div class="col-xs-12 col-sm-3">
                <Input
                    label="CNPJ"
                    v-model="form.cnpj"
                    :error="form.errors.cnpj"
                    v-maska="'##.###.###/####-##'"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <Input
                    label="Insc. Est."
                    v-model="form.insc_est"
                    :error="form.errors.insc_est"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Input
                    type="email"
                    label="E-mail"
                    v-model="form.email"
                    :error="form.errors.email"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Input
                    label="Telefone"
                    v-model="form.phone"
                    :error="form.errors.phone"
                    v-maska="['(##) ####-####', '(##) #####-####']"
                />
            </div>
        </div>

        <AddressForm
            v-model:address="form.address"
            v-model:errors="form.errors"
        />

        <Input label="Observações" v-model="form.observations" rows="3" />

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                {{ supplier ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link class="btn btn-secondary" :href="route('suppliers.index')">
                Voltar
            </Link>
        </div>
    </form>
</template>
