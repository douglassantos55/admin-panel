<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    transporters: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Transportadores</h1>

        <Link :href="route('transporters.create')" class="btn btn-primary">
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
                <tr v-for="transporter in transporters.data" :key="transporter.id">
                    <td>{{ transporter.name }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <Link
                                :href="route('transporters.edit', transporter.id)"
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

    <Pagination :links="transporters.links" :last-page="transporters.last_page" />
</template>
