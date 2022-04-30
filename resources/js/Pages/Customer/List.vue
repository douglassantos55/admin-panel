<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Clientes</h1>
        <Link :href="route('customers.create')" class="btn btn-primary">Cadastrar</Link>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF/CNPJ</th>
                    <th>RG/Insc. Est.</th>
                    <th>E-mail</th>
                    <th>Telefone<br>Celular</th>
                    <th>Endereço</th>
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
                        <td>{{ customer.rg_insc_est }}</td>
                        <td>{{ customer.email }}</td>
                        <td>
                            <span class="d-block" v-if="customer.phone">
                                {{ customer.phone }}
                            </span>

                            <span class="d-block" v-if="customer.cellphone">
                                {{ customer.cellphone }}
                            </span>
                        </td>
                        <td>{{ formatAddress(customer.address) }}</td>
                        <td>
                            <Link class="btn btn-secondary" :href="route('customers.edit', customer.id)">
                                Editar
                            </Link>

                            <Link as="button" class="btn btn-danger" :href="route('customers.destroy', customer.id)" method="delete">
                                Excluir
                            </Link>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <nav>
            <ul class="pagination justify-content-center">
                <li :class="['page-item', { disabled: !link.url, active: link.active }]" v-for="link in customers.links">
                    <Link :href="link.url" class="page-link" v-html="link.label" />
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link,
    },
    props: {
        customers: {
            type: Array,
            required: true,
        },
    },
    methods: {
        formatAddress(address) {
            return [
                address.postcode,
                address.neighborhood,
                address.city,
                address.state,
            ].filter(value => value).join(', ')
        },
    },
}
</script>
