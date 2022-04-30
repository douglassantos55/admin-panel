<script setup>
import { watch } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import useForm from '../../Composables/useForm'
import AddressForm from '../../Components/AddressForm.vue'

const { customer } = defineProps({
    customer: Object,
})

const { form, submit } = useForm(customer || {
    name: '',
    email: '',
    cpf_cnpj: '',
    rg_insc_est: '',
    phone: '',
    cellphone: '',
    ocupation: '',
    birthdate: '',
    address: {
        postcode: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: '',
    },
    observations: '',
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>{{ customer ? 'Editar' : 'Cadastrar' }} cliente</h1>
        <Link :href="route('customers.index')" class="btn btn-link">Voltar</Link>
    </div>

    <form @submit.prevent="submit('customers')">
        <div class="row">
            <Input
                required
                label="Nome"
                class="col-md-4"
                v-model="form.name"
                :error="form.errors.name"
            />

            <Input
                required
                class="col-md-4"
                label="CPF/CNPJ"
                v-model="form.cpf_cnpj"
                :error="form.errors.cpf_cnpj"
                v-maska="['###.###.###-##', '##.###.###/####-##']"
            />

            <Input
                class="col-md-4"
                label="RG/Insc. Est."
                v-model="form.rg_insc_est"
            />
        </div>

        <div class="row">
            <Input
                class="col-md-4"
                label="E-mail"
                v-model="form.email"
                :error="form.errors.email"
            />

            <Input
                label="Telefone"
                class="col-md-4"
                v-model="form.phone"
                v-maska="'(##) ####-####'"
            />

            <Input
                label="Celular"
                class="col-md-4"
                v-model="form.cellphone"
                v-maska="'(##) #####-####'"
            />
        </div>

        <div class="row">
            <Input
                class="col-md-6"
                label="Profissao"
                v-model="form.ocupation"
            />

            <Input
                type="date"
                class="col-md-6"
                label="Data de nascimento"
                v-model="form.birthdate"
            />
        </div>

        <AddressForm
            v-model:address="form.address"
            v-model:errors="form.errors"
        />

        <div class="mb-4 d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                {{ customer ? 'Editar' : 'Cadastrar' }}
            </button>
            <Link :href="route('customers.index')" class="btn btn-secondary">Voltar</Link>
        </div>
    </form>
</template>
