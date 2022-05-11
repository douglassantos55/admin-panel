<script setup>
import { reactive, watch, computed } from 'vue'
import { format } from '../../utils'
import useForm from '../../Composables/useForm'
import useMap from '../../Composables/useMap'
import { Link } from '@inertiajs/inertia-vue3'
import RentItem from './RentItem.vue'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
    rent: {
        type: Object,
        required: false,
    },
    customers: {
        type: Array,
        required: true,
    },
    periods: {
        type: Array,
        required: true,
    },
    payment_types: {
        type: Array,
        required: true,
    },
    payment_conditions: {
        type: Array,
        required: true,
    },
    payment_methods: {
        type: Array,
        required: true,
    },
    transporters: {
        type: Array,
        required: true,
    },
    equipments: {
        type: Array,
        required: true,
    },
})

const search = useMap(props.equipments)

function createItem(data = { qty: '', equipment_id: '' }) {
    const item = reactive(data)

    watch(() => [
        item.equipment_id,
        form.period_id,
        form.payment_condition_id
    ], function ([equipment_id, period_id, condition_id]) {
        const equipment = search(equipment_id)

        item.weight = equipment && equipment.weight
        item.unit_value = equipment && equipment.unit_value

        const rent_value = equipment && equipment.values.find(function (v) {
            return v.period_id == period_id
        })

        const condition = props.payment_conditions.find(function (condition) {
            return condition.id == condition_id
        })

        const multiplier = 1 + (condition && condition.increment / 100 || 0)
        item.rent_value = rent_value && rent_value.value * multiplier
    })

    item.subtotal_weight = computed(() => {
        return item.weight * item.qty
    })

    item.subtotal_unit_value = computed(() => {
        return item.unit_value * item.qty
    })

    item.subtotal_rent_value = computed(() => {
        return item.rent_value * item.qty
    })

    return item
}

const { form, submit } = useForm(props.rent || {
    customer_id: '',
    period_id: '',
    start_date: '',
    start_hour: '',
    end_date: '',
    end_hour: '',
    payment_type_id: '',
    payment_method_id: '',
    payment_condition_id: '',
    qty_days: '',
    transporter_id: '',
    delivery_address: '',
    usage_address: '',
    delivery_value: '',
    discount: '',
    discount_reason: '',
    paid_value: '',
    bill: '',
    check_info: '',
    observations: '',
    items: [],
})

form.items = form.items.map(function (item) {
    return createItem(item)
})

const filteredConditions = computed(function () {
    return props.payment_conditions.filter(function (condition) {
        return condition.payment_type_id == form.payment_type_id;
    })
})

const filteredEquipment = computed(function () {
    return props.equipments.filter(function (equip) {
        return equip.values.find(function (value) {
            return value.period_id == form.period_id
        })
    })
})

const subtotal_weight = computed(() => {
    return form.items.reduce((total, item) => {
        return total + (parseFloat(item.subtotal_weight) || 0)
    }, 0)
})

const subtotal_unit_value = computed(() => {
    return form.items.reduce((total, item) => {
        return total + (parseFloat(item.subtotal_unit_value) || 0)
    }, 0)
})

const subtotal_rent_value = computed(() => {
    return form.items.reduce((total, item) => {
        return total + (parseFloat(item.subtotal_rent_value) || 0)
    }, 0)
})

const subtotal_qty = computed(() => {
    return form.items.reduce((total, item) => {
        return total + (parseInt(item.qty) || 0)
    }, 0)
})

const requires_delivery = computed(() => {
    return props.transporters.find(function (transporter) {
        return transporter.id == form.transporter_id && Boolean(transporter.delivery)
    })
})

const total = computed(() => {
    const delivery_value = parseFloat(form.delivery_value) || 0
    const discount = parseFloat(form.discount) || 0
    return subtotal_rent_value.value + delivery_value - discount
})

const remaining = computed(() => {
    return total.value - (parseFloat(form.paid_value) || 0)
})

const change = computed(() => {
    const bill = parseFloat(form.bill) || 0
    const paid_value = parseFloat(form.paid_value) || 0
    return bill - paid_value
})

const paid_with_check = computed(() => {
    const payment_method = props.payment_methods.find(function (method) {
        return method.id == form.payment_method_id
    })
    return payment_method && /cheque/i.test(payment_method.name)
})

watch(form, function() {
    const triggers = {
        paid_value: 'bill',
        discount: 'discount_reason',
    }

    for (const field in triggers) {
        if (!form[field]) {
            form[triggers[field]] = ''
        }
    }
})

watch(() => form.payment_type_id, () => {
    form.payment_condition_id = ''
})

watch([paid_with_check, requires_delivery], () => {
    if (!requires_delivery.value) {
        form.delivery_address = ''
    }

    if (!paid_with_check.value) {
        form.check_info = ''
    }
})

watch(() => form.start_hour, startHour => {
    form.end_hour = startHour
})

watch(() => [form.start_date, form.period_id], ([ dateString, periodId ]) => {
    const period = props.periods.find(period => period.id == periodId)

    if (period) {
        form.qty_days = period.qty_days

        if (dateString && periodId) {
            const dateObj = new Date(dateString)
            const period = props.periods.find(period => period.id == periodId)

            dateObj.setDate(dateObj.getDate() + period.qty_days)

            form.end_date = [
                dateObj.getUTCFullYear(),
                (dateObj.getUTCMonth()+1).toString().padStart(2, '0'),
                dateObj.getUTCDate().toString().padStart(2, '0')
            ].join('-')
        }
    }
})
</script>

<template>
    <h1 class="mb-4">Cadastrar locação</h1>

    <form @submit.prevent="submit('rents')">
        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <Select
                    required
                    label="Cliente"
                    v-model="form.customer_id"
                    :options="customers"
                    placeholder="Escolha o cliente"
                    :error="form.errors.customer_id"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Select
                    required
                    label="Período"
                    data-test="periodo"
                    v-model="form.period_id"
                    :options="periods"
                    placeholder="Escolha o período"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    required
                    type="date"
                    data-test="data_inicio"
                    label="Data início"
                    v-model="form.start_date"
                    :error="form.errors.start_date"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    required
                    type="time"
                    label="Hora início"
                    data-test="hora_inicio"
                    v-model="form.start_hour"
                    :error="form.errors.start_hour"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <Input
                    required
                    type="date"
                    data-test="data_termino"
                    label="Data término"
                    v-model="form.end_date"
                    :error="form.errors.end_date"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    readonly
                    type="time"
                    label="Hora término"
                    data-test="hora_termino"
                    v-model="form.end_hour"
                    :error="form.errors.end_hour"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Select
                    required
                    data-test="tipo_pagamento"
                    label="Tipo pagamento"
                    v-model="form.payment_type_id"
                    :options="payment_types"
                    placeholder="Escolha o tipo de pagamento"
                    :error="form.errors.payment_type_id"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Select
                    required
                    data-test="condicao_pagamento"
                    label="Condição pagamento"
                    v-model="form.payment_condition_id"
                    :options="filteredConditions"
                    placeholder="Escolha a condição de pagamento"
                    :error="form.errors.payment_condition_id"
                />
            </div>
        </div>

        <div class="table-responsive my-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Descrição</th>
                        <th>Qtd</th>
                        <th>Peso (kg)</th>
                        <th>Valor unit</th>
                        <th>Valor loc</th>
                        <th>Subtotal peso</th>
                        <th>Subtotal unit</th>
                        <th>Subtotal loc</th>
                    </tr>
                </thead>

                <tbody>
                    <RentItem
                        :key="idx"
                        :error="{}"
                        :item="form.items[idx]"
                        :equipment="filteredEquipment"
                        v-for="(item, idx) in form.items"
                        @remove-item="form.items.splice(idx, 1)"
                    />

                    <tr class="fw-bold">
                        <td colspan="6"></td>

                        <td data-test="subtotal_weight_items">
                            <span v-if="subtotal_weight">
                                {{ format(subtotal_weight) }}
                            </span>
                        </td>

                        <td data-test="subtotal_unit_value_items">
                            <span v-if="subtotal_unit_value">
                                {{ format(subtotal_unit_value) }}
                            </span>
                        </td>

                        <td data-test="subtotal_rent_value_items">
                            <span v-if="subtotal_rent_value">
                                {{ format(subtotal_rent_value) }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button
                type="button"
                class="btn btn-primary"
                data-test="add_item"
                @click="form.items.push(createItem())"
                :disabled="!form.payment_condition_id"
            >
                Adicionar item
            </button>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    readonly
                    data-test="peso_total"
                    label="Peso total (kg)"
                    :modelValue="subtotal_weight"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    readonly
                    type="number"
                    label="Total peças"
                    data-test="total_pecas"
                    :modelValue="subtotal_qty"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    readonly
                    type="number"
                    label="Qtd itens"
                    data-test="qtd_itens"
                    :modelValue="form.items.length"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="Valor total bens"
                    data-test="valor_total_bens"
                    :modelValue="subtotal_unit_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="Subtotal locação"
                    data-test="subtotal_locacao"
                    :modelValue="subtotal_rent_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    label="Qtd dias"
                    data-test="qtd_dias"
                    v-model="form.qty_days"
                    :error="form.errors.qty_days"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-4">
                <Select
                    label="Transportador"
                    :options="transporters"
                    data-test="transportador"
                    v-model="form.transporter_id"
                    :error="form.errors.transporter_id"
                    placeholder="Escolha o transportador"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <Input
                    v-if="requires_delivery"
                    label="Endereço entrega"
                    data-test="endereco_entrega"
                    v-model="form.delivery_address"
                    :error="form.errors.delivery_address"
                />
            </div>

            <div class="col-xs-12">
                <Input
                    label="Local utilização"
                    v-model="form.usage_address"
                    :error="form.errors.usage_address"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2" v-if="requires_delivery">
                <DecimalInput
                    currency
                    label="Valor transporte"
                    data-test="valor_transporte"
                    v-model="form.delivery_value"
                    :error="form.errors.delivery_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Desconto"
                    data-test="desconto"
                    v-model="form.discount"
                    :error="form.errors.discount"
                />
            </div>

            <div class="col-xs-12 col-sm-2" v-if="form.discount">
                <Input
                    label="Razão do desconto"
                    data-test="razao_desconto"
                    v-model="form.discount_reason"
                    :error="form.errors.discount_reason"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    readonly
                    currency
                    :modelValue="total"
                    label="Total contrato"
                    data-test="total_contrato"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Select
                    required
                    label="Forma pagamento"
                    :options="payment_methods"
                    data-test="forma_pagamento"
                    v-model="form.payment_method_id"
                    :error="form.errors.payment_method_id"
                    placeholder="Escolha a forma de pagamento"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor pago"
                    data-test="valor_pago"
                    v-model="form.paid_value"
                    :error="form.errors.paid_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2" v-if="form.paid_value">
                <DecimalInput
                    currency
                    label="Cédula"
                    data-test="cedula"
                    v-model="form.bill"
                    :error="form.errors.bill"
                />
            </div>

            <div class="col-xs-12 col-sm-2" v-if="form.bill > form.paid_value">
                <DecimalInput
                    currency
                    readonly
                    label="Troco"
                    data-test="troco"
                    :modelValue="change"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="À receber"
                    data-test="a_receber"
                    :modelValue="remaining"
                />
            </div>

            <div class="col-xs-12 col-sm-6" v-if="paid_with_check">
                <Input
                    label="Dados cheque(s)"
                    v-model="form.check_info"
                    data-test="dados_cheque"
                    :error="form.errors.check_info"
                />
            </div>
        </div>

        <Input
            rows="4"
            label="Observações"
            v-model="form.observations"
            :error="form.errors.observations"
        />

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary">Cadastrar</button>

            <Link href="#" class="btn btn-secondary">Voltar</Link>
        </div>
    </form>
</template>
