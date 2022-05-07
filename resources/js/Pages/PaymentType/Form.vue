<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
    payment_type: {
        type: Object,
        required: false,
    },
})

const { form, submit } = useForm(props.payment_type || {
    name: '',
})
</script>

<template>
    <h1 class="mb-4">
        {{ payment_type ? 'Editar' : 'Cadastrar' }} tipo de pagamento
    </h1>

    <form @submit.prevent="submit('payment_types')">
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
                {{ payment_type ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link :href="route('payment_types.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
