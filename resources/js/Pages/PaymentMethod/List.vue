<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    payment_methods: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Formas de pagamento</h1>

        <Link :href="route('payment_methods.create')" class="btn btn-primary">
            Cadastrar
        </Link>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="method in payment_methods.data" :key="method.id">
                    <td>{{ method.name }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <Link
                                href=""
                                class="btn btn-sm btn-secondary"
                            >
                                Editar
                            </Link>

                            <Link
                                as="button"
                                method="delete"
                                class="btn btn-sm btn-danger"
                                href=""
                            >
                                Excluir
                            </Link>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="payment_methods.links" :last-page="payment_methods.last_page" />
</template>
