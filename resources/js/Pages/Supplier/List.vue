<script setup>
import { Link } from '@inertiajs/inertia-vue3'

defineProps({
    suppliers: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Fornecedores</h1>

        <Link :href="route('suppliers.create')" class="btn btn-primary">
            Cadastrar
        </Link>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CNPJ</th>
                    <th>Telefone</th>
                    <th>Endereço</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="supplier in suppliers.data" :key="supplier.id">
                    <td>{{ supplier.social_name }}</td>
                    <td>{{ supplier.email }}</td>
                    <td>{{ supplier.cnpj }}</td>
                    <td>{{ supplier.phone }}</td>
                    <td>{{ supplier.street }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <nav>
        <ul class="pagination justify-content-center">
            <li :class="['page-item', { disabled: !link.url, active: link.active }]" v-for="link in suppliers.links">
                <Link :href="link.url" class="page-link" v-html="link.label" preserve-state />
            </li>
        </ul>
    </nav>
</template>
