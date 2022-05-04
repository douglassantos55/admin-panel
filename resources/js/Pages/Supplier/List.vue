<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Filter from '../../Components/Filter.vue'
import Address from '../../Components/Address.vue'
import Pagination from '../../Components/Pagination.vue'

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

    <Filter class="mb-3" :filters="{ name: '', cnpj: '' }" v-slot="{ filters }">
        <div class="col-xs-12 col-sm-5">
            <Input label="Nome" v-model="filters.name" placeholder="Nome" />
        </div>

        <div class="col-xs-12 col-sm-4">
            <Input
                label="CNPJ"
                placeholder="CNPJ"
                v-model="filters.cnpj"
                v-maska="'##.###.###/####-##'"
            />
        </div>
    </Filter>

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
                <tr v-if="suppliers.data.length === 0">
                    <td class="text-center" colspan="6">Nenhum período cadastrado até o momento.</td>
                </tr>

                <template v-else>
                    <tr v-for="supplier in suppliers.data" :key="supplier.id">
                        <td>
                            {{ supplier.social_name }}
                            <small class="d-block text-muted">
                                {{ supplier.legal_name }}
                            </small>
                        </td>
                        <td>{{ supplier.email }}</td>
                        <td>{{ supplier.cnpj }}</td>
                        <td>{{ supplier.phone }}</td>
                        <td>
                            <Address :address="supplier.address" />
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <Link :href="route('suppliers.edit', supplier.id)" class="btn btn-sm btn-secondary">
                                    Editar
                                </Link>

                                <Link as="button" :href="route('suppliers.destroy', supplier.id)" method="delete" class="btn btn-sm btn-danger">
                                    Excluir
                                </Link>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <Pagination :links="suppliers.links" :last-page="suppliers.last_page" />
</template>
