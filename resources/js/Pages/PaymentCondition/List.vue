<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    payment_conditions: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Condições de pagamento</h1>

        <Link :href="route('payment_conditions.create')" class="btn btn-primary">
            Cadastrar
        </Link>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Titulo</th>
                    <th>Tipo</th>
                    <th>Taxa</th>
                    <th>Parcelas</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="condition in payment_conditions.data" :key="condition.id">
                    <td>{{ condition.name }}</td>
                    <td>{{ condition.title }}</td>
                    <td>{{ condition.payment_type.name }}</td>
                    <td>{{ condition.increment }}</td>
                    <td>{{ condition.installments }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <Link
                                :href="route('payment_conditions.edit', condition.id)"
                                class="btn btn-sm btn-secondary"
                            >
                                Editar
                            </Link>

                            <Link
                                as="button"
                                method="delete"
                                class="btn btn-sm btn-danger"
                                :href="route('payment_conditions.destroy', condition.id)"
                            >
                                Excluir
                            </Link>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <Pagination :links="payment_conditions.links" :last-page="payment_conditions.last_page" />
</template>
