<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import Filter from '../../Components/Filter.vue'
import Pagination from '../../Components/Pagination.vue'
import { formatDate, formatCurrency } from '../../utils'

defineProps({
    rents: {
        type: Object,
        required: true,
    },
    customers: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Locações</h1>
        <Link :href="route('rents.create')" class="btn btn-primary">Cadastrar</Link>
    </div>

    <Filter class="mb-3" :filters="{ number: '', customer: '' }" v-slot="{ filters }">
        <div class="col-xs-12 col-sm-5">
            <Input label="Número locação" v-model="filters.number" placeholder="Número locação" />
        </div>

        <div class="col-xs-12 col-sm-4">
            <Select
                label="Cliente"
                placeholder="Cliente"
                v-model="filters.customer"
                :options="customers"
            />
        </div>
    </Filter>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Período</th>
                    <th class="text-end">Valor total bens</th>
                    <th class="text-end">Valor locação</th>
                    <th class="text-end">Valor contrato</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="rents.data.length === 0">
                    <td class="text-center" colspan="7">Nenhuma locação cadastrada até o momento.</td>
                </tr>

                <template v-else>
                    <tr v-for="rent in rents.data" :key="rent.id">
                        <td>{{ rent.number }}</td>
                        <td>{{ rent.customer.name }}</td>
                        <td>
                            Início: {{ formatDate(rent.start_date) }}<br />
                            Término: {{ formatDate(rent.end_date) }}
                        </td>
                        <td>{{ rent.period.name }}</td>
                        <td class="text-end">{{ formatCurrency(rent.total_unit_value) }}</td>
                        <td class="text-end">{{ formatCurrency(rent.total_rent_value) }}</td>
                        <td class="text-end">{{ formatCurrency(rent.total) }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end">
                                <Link class="btn btn-sm btn-primary" :href="route('rents.view', rent.id)">
                                    Visualizar
                                </Link>

                                <Link as="button" class="btn btn-sm btn-danger" href="" method="delete">
                                    Excluir
                                </Link>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <Pagination :links="rents.links" :last-page="rents.last_page" />
</template>

