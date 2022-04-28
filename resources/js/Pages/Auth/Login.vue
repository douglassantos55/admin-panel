<template>
    <div class="bg-dark d-flex align-items-center justify-content-between vh-100">
        <div class="box p-4 bg-light rounded-3">
            <h2 class="mb-4 text-center">Reconcip</h2>

            <form @submit.prevent="submit">
                <Input
                    type="email"
                    label="E-mail"
                    v-model="form.email"
                    :error="form.errors.email"
                />

                <Input
                    label="Senha"
                    type="password"
                    v-model="form.password"
                    :error="form.errors.password"
                />

                <button type="submit" class="w-100 btn btn-primary">Entrar</button>

                <Link :href="route('reset.password')" class="w-100 mt-3 btn btn-link">
                    Esqueci minha senha
                </Link>
            </form>
        </div>
    </div>
</template>

<script>
import Layout from './Layout.vue'
import Input from '../../Components/Input.vue'
import { useForm } from '@inertiajs/inertia-vue3'

export default {
    layout: Layout,
    components: {
        Input,
    },
    setup() {
        const form = useForm({
            email: '',
            password: '',
        })

        function submit() {
            form.clearErrors()
            form.post(route('authenticate'), { preserveScroll: true })
        }

        return { form, submit }
    },
}
</script>

<style scoped>
.box {
    margin: auto;
    max-width: 330px;
}
</style>
