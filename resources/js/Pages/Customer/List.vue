<script setup>
import { reactive } from 'vue'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Address from '../../Components/Address.vue'

const props = defineProps({
    customers: {
        type: Object,
        required: true,
    },
})

const filters = reactive({
    name: '',
    cpf_cnpj: '',
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Clientes</h1>
        <Link :href="route('customers.create')" class="btn btn-primary">Cadastrar</Link>
    </div>

    <form>
        <div class="row">
            <div class="col-xs-12 col-sm-5">
                <Input label="" v-model="filters.name" placeholder="Nome" />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Input
                    label=""
                    placeholder="CPF/CNPJ"
                    v-model="filters.cpf_cnpj"
                    v-maska="['###.###.###-##', '##.###.###/####-##']"
                />
            </div>

            <div class="col-xs-12 col-sm-3">
                <Link
                    as="button"
                    preserve-state
                    class="btn btn-secondary"
                    :data="filters"
                >
                    Filtrar
                </Link>

                <Link
                    class="btn btn-link"
                    :href="route('customers.index')"
                    v-if="filters.name || filters.cpf_cnpj"
                >
                    Limpar filtros
                </Link>
            </div>
        </div>
    </form>

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
                    <td class="text-center" colspan="6">Nenhum cliente cadastrado até o momento.</td>
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

    <nav>
        <ul class="pagination justify-content-center">
            <li :class="['page-item', { disabled: !link.url, active: link.active }]" v-for="link in customers.links">
                <Link :href="link.url" class="page-link" v-html="link.label" preserve-state />
            </li>
        </ul>
    </nav>
</template>

