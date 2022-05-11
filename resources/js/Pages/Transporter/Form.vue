<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'

const props = defineProps({
    transporter: {
        type: Object,
        required: false,
    },
})

const { form, submit } = useForm(props.transporter || {
    name: '',
    delivery: false,
})
</script>

<template>
    <h1 class="mb-4">
        {{ transporter ? 'Editar' : 'Cadastrar' }} Transportador
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

        <div class="row">
            <div class="col-xs-12">
                <div class="mb-4 form-check form-switch">
                    <input
                        id="delivery"
                        role="switch"
                        type="checkbox"
                        class="form-check-input"
                        v-model="form.delivery"
                    />

                    <label class="form-check-label" for="delivery">
                        Necessita endereço de entrega
                    </label>
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">
                {{ transporter ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link :href="route('transporters.index')" class="btn btn-secondary">
                Voltar
            </Link>
        </div>
    </form>
</template>
