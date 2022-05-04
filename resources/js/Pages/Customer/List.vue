<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Filter from '../../Components/Filter.vue'
import Address from '../../Components/Address.vue'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    customers: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Clientes</h1>
        <Link :href="route('customers.create')" class="btn btn-primary">Cadastrar</Link>
    </div>

    <Filter class="mb-3" :filters="{ name: '', cpf_cnpj: '' }" v-slot="{ filters }">
        <div class="col-xs-12 col-sm-5">
            <Input label="Nome" v-model="filters.name" placeholder="Nome" />
        </div>

        <div class="col-xs-12 col-sm-4">
            <Input
                label="CPF/CNPJ"
                placeholder="CPF/CNPJ"
                v-model="filters.cpf_cnpj"
                v-maska="['###.###.###-##', '##.###.###/####-##']"
            />
        </div>
    </Filter>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF/CNPJ</th>
                    <th>E-mail</th>
                    <th>Endereço</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="customers.data.length === 0">
                    <td class="text-center" colspan="5">Nenhum cliente cadastrado até o momento.</td>
                </tr>

                <template v-else>
                    <tr v-for="customer in customers.data" :key="customer.id">
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.cpf_cnpj }}</td>
                        <td>{{ customer.email }}</td>
                        <td>
                            <Address :address="customer.address" />
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <Link class="btn btn-sm btn-secondary" :href="route('customers.edit', customer.id)">
                                    Editar
                                </Link>

                                <Link as="button" class="btn btn-sm btn-danger" :href="route('customers.destroy', customer.id)" method="delete">
                                    Excluir
                                </Link>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <Pagination :links="customers.links" :last-page="customers.last_page" />
</template>

