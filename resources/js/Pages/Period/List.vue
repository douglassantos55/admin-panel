<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    periods: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Períodos</h1>

        <Link :href="route('periods.create')" class="btn btn-primary">
            Cadastrar
        </Link>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Qtd dias</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="periods.data.length === 0">
                    <td class="text-center" colspan="2">Nenhum período cadastrado até o momento.</td>
                </tr>

                <template v-else>
                    <tr v-for="period in periods.data" :key="period.id">
                        <td>{{ period.name }}</td>
                        <td>{{ period.qty_days }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end">
                                <Link
                                    class="btn btn-sm btn-secondary"
                                    :href="route('periods.edit', period.id)"
                                >
                                    Editar
                                </Link>

                                <Link
                                    as="button"
                                    method="delete"
                                    class="btn btn-sm btn-danger"
                                    :href="route('periods.destroy', period.id)"
                                >
                                    Excluir
                                </Link>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <Pagination :links="periods.links" :last-page="periods.last_page" />
</template>
