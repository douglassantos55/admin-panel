<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    payment_types: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Tipos de pagamento</h1>

        <Link :href="route('payment_types.create')" class="btn btn-primary">
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
                <tr v-for="paymentType in payment_types.data" :key="paymentType.id">
                    <td>{{ paymentType.name }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <Link
                                :href="route('payment_types.edit', paymentType.id)"
                                class="btn btn-sm btn-secondary"
                            >
                                Editar
                            </Link>

                            <Link
                                as="button"
                                method="delete"
                                class="btn btn-sm btn-danger"
                                :href="route('payment_types.destroy', paymentType.id)"
                            >
                                Excluir
                            </Link>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="payment_types.links" :last-page="payment_types.last_page" />
</template>
