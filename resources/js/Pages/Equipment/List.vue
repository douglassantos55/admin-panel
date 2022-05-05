<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import Filter from '../../Components/Filter.vue'
import Pagination from '../../Components/Pagination.vue'

defineProps({
    equipments: {
        type: Object,
        required: true,
    },
    suppliers: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Equipamentos</h1>
        <Link :href="route('equipments.create')" class="btn btn-primary">Cadastrar</Link>
    </div>

    <Filter class="mb-3" :filters="{ description: '', supplier: '' }" v-slot="{ filters }">
        <div class="col-xs-12 col-sm-5">
            <Input label="Descrição" v-model="filters.description" />
        </div>

        <div class="col-xs-12 col-sm-4">
            <Select
                label="Fornecedor"
                v-model="filters.supplier"
                :options="suppliers"
                textBy="social_name"
            />
        </div>
    </Filter>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Unidade</th>
                    <th>Descrição</th>
                    <th>Fornecedor</th>
                    <th>Estoque</th>
                    <th>Qtd efetiva</th>
                    <th>Valor compra</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr v-if="equipments.data.length === 0">
                    <td class="text-center" colspan="3">Nenhum equipamento cadastrado até o momento.</td>
                </tr>

                <template v-else>
                    <tr v-for="equipment in equipments.data" :key="equipment.id">
                        <td>{{ equipment.unit }}</td>
                        <td>{{ equipment.description }}</td>
                        <td><span v-if="equipment.supplier">{{ equipment.supplier.social_name }}</span></td>
                        <td>{{ equipment.in_stock }}</td>
                        <td>{{ equipment.effective_qty }}</td>
                        <td>{{ equipment.purchase_value }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end">
                                <Link class="btn btn-sm btn-secondary" :href="route('equipments.edit', equipment.id)">
                                    Editar
                                </Link>

                                <Link as="button" class="btn btn-sm btn-danger" :href="route('equipments.destroy', equipment.id)" method="delete">
                                    Excluir
                                </Link>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <Pagination :links="equipments.links" :last-page="equipments.last_page" />
</template>
