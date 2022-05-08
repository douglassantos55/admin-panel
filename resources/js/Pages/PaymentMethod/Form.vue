<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'

const props = defineProps({
    payment_method: {
        type: Object,
        required: false,
    },
})

const { form, submit } = useForm(props.payment_method || {
    name: '',
})
</script>

<template>
    <h1 class="mb-4">
        {{ payment_method ? 'Editar' : 'Cadastrar' }} forma de pagamento
    </h1>

    <form @submit.prevent="submit('payment_methods')">
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
                {{ payment_method ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link :href="route('payment_types.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
