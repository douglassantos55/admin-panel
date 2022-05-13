<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { format, formatDate, formatCurrency } from '../../utils'

defineProps({
    rent: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <div class="mb-4 d-flex align-items-center justify-content-between">
        <h1>Locação #{{ rent.number }}</h1>

        <div class="d-flex gap-2">
            <Link :href="route('rents.edit', rent.id)" class="btn btn-secondary">
                Editar locação
            </Link>

            <Link href="" class="btn btn-primary">
                Contrato Jurídico
            </Link>

            <Link href="" class="btn btn-success">
                Contrato Físico
            </Link>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-4 card-title">Informações</h4>

            <table class="table align-middle">
                <tbody>
                    <tr>
                        <td><b>Cliente</b>: {{ rent.customer.name }}</td>
                        <td><b>Período</b>: {{ rent.period.name }}</td>
                        <td><b>Início locação</b>: {{ formatDate(rent.start_date) }}</td>
                        <td><b>Término locação</b>: {{ formatDate(rent.end_date) }}</td>
                    </tr>

                    <tr>
                        <td><b>Qtd dias locados</b>: {{ rent.qty_days }}</td>
                        <td><b>Valor total dos bens</b>: {{ formatCurrency(rent.total_unit_value) }}</td>
                        <td><b>Valor locação/Dia</b>: {{ formatCurrency(rent.total_rent_value / rent.qty_days) }}</td>
                        <td><b>Valor total locação</b>: {{ formatCurrency(rent.total_rent_value) }}</td>
                    </tr>

                    <tr>
                        <td><b>Valor transporte</b>: {{ formatCurrency(rent.delivery_value) }}</td>
                        <td><b>Desconto</b>: {{ formatCurrency(rent.discount) }}</td>
                        <td><b>Razão do desconto</b>: {{ rent.discount_reason }}</td>
                        <td><b>Valor contrato</b>: {{ formatCurrency(rent.total) }}</td>
                    </tr>

                    <tr>
                        <td><b>Condição Pagam/o</b>: {{ rent.payment_condition.title }}</td>
                        <td><b>Forma Pagam/o</b>: {{ rent.payment_method.name }}</td>
                        <td><b>Valor pago</b>: {{ formatCurrency(rent.paid_value) }}</td>
                        <td><b>Cédula</b>: {{ formatCurrency(rent.bill) }}</td>
                    </tr>

                    <tr>
                        <td><b>Troco</b>: {{ formatCurrency(rent.change) }}</td>
                        <td><b>À receber</b>: {{ formatCurrency(rent.remaining) }}</td>
                        <td><b>Qtd itens</b>: {{ rent.items.length }}</td>
                        <td><b>Total peças</b>: {{ rent.total_pieces }}</td>
                    </tr>

                    <tr>
                        <td><b>Peso total</b>: {{ format(rent.total_weight) }}</td>
                        <td colspan="3"><b>Transportador(a)</b>: {{ rent.transporter.name }}</td>
                    </tr>

                    <tr v-if="rent.check_info">
                        <td colspan="4"><b>Dados cheque(s)</b>: {{ rent.check_info }}</td>
                    </tr>

                    <tr v-if="rent.observations">
                        <td colspan="4"><b>Observações</b>: {{ rent.observations }}</td>
                    </tr>

                    <tr v-if="rent.transporter.delivery">
                        <td colspan="4">
                            <b>{{ rent.delivery_address ? "Endereço de entrega:" : "Endereço de entrega e utilização:" }}</b>
                            {{ rent.delivery_address || rent.usage_address }}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">
                            <b>Endereço de utilização:</b>
                            {{ rent.usage_address || "Mesmo endereço de entrega" }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-4 card-title">Itens locação</h4>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th class="text-end">Peso (kg)</th>
                        <th class="text-end">Valor unit</th>
                        <th class="text-end">Valor loc</th>
                        <th class="text-end">Subtotal peso</th>
                        <th class="text-end">Subtotal unit</th>
                        <th class="text-end">Subtotal loc</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, key) in rent.items" :key="key">
                        <td>
                            {{ item.equipment.description }}
                        </td>

                        <td>
                            {{ item.qty }}
                        </td>

                        <td class="text-end">
                            {{ format(item.equipment.weight) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(item.unit_value) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(item.rent_value) }}
                        </td>

                        <td class="text-end">
                            {{ format(item.subtotal_weight) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(item.subtotal_unit_value) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(item.subtotal_rent_value) }}
                        </td>
                    </tr>

                    <tr class="bold">
                        <td colspan="5" class="hidden-mobile"></td>

                        <td class="text-end">
                            {{ format(rent.total_weight) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(rent.total_unit_value) }}
                        </td>

                        <td class="text-end">
                            {{ formatCurrency(rent.total_rent_value) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <Link :href="route('rents.index')" class="btn btn-secondary">Voltar</Link>
</template>
