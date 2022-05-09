<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'

const { form, submit } = useForm({
    name: '',
})
</script>

<template>
    <h1 class="mb-4">
        Cadastrar Transportador
    </h1>

    <form @submit.prevent="submit('transporters')">
        <div class="row">
            <div class="col-xs-12">
                <Input
                    required
                    label="Nome"
                    v-model="form.name"
                    :error="form.errors.name"
                />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                Cadastrar
            </button>

            <Link :href="route('transporters.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
