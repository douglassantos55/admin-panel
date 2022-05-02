<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import useForm from '../../Composables/useForm'

const props = defineProps({
    period: Object,
})

const { form, submit } = useForm(props.period || {
    name: '',
    qty_days: '',
})
</script>

<template>
    <h1 class="mb-4">{{ period ? 'Editar' : 'Cadastrar' }} período</h1>

    <form @submit.prevent="submit('periods')">
        <div class="row">
            <div class="col-xs-12 col-sm-8">
                <Input
                    label="Nome"
                    v-model="form.name"
                    :error="form.errors.name"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Input
                    type="number"
                    label="Qtd dias"
                    v-model="form.qty_days"
                    :error="form.errors.qty_days"
                />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                {{ period ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link :href="route('periods.index')" class="btn btn-secondary">Voltar</Link>
        </div>
    </form>
</template>
