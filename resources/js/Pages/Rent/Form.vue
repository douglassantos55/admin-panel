<script setup>
import useForm from '../../Composables/useForm'
import { Link } from '@inertiajs/inertia-vue3'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
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

const { form, submit } = useForm({
    customer_id: '',
    period_id: '',
    start_date: '',
    start_hour: '',
    end_date: '',
    end_hour: '',
    payment_type_id: '',
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
                    v-model="form.period_id"
                    :options="periods"
                    placeholder="Escolha o período"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    required
                    type="date"
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
                    label="Data término"
                    v-model="form.end_date"
                    :error="form.errors.end_date"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    required
                    type="time"
                    label="Hora término"
                    v-model="form.end_hour"
                    :error="form.errors.end_hour"
                />
            </div>

            <div class="col-xs-12 col-sm-4">
                <Select
                    required
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
                    label="Condição pagamento"
                    v-model="form.payment_condition_id"
                    :options="payment_conditions"
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
                    <tr>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger">
                                Remover
                            </button>
                        </td>

                        <td class="">
                            <Select
                                required
                                label=""
                                :options="equipments"
                                textBy="description"
                            />
                        </td>

                        <td width="100">
                            <Input
                                required
                                label=""
                                type="number"
                            />
                        </td>

                        <td class="text-right">
                            100
                        </td>

                        <td class="text-right">
                            30
                        </td>

                        <td class="text-right">
                            50
                        </td>

                        <td class="text-right">
                            200
                        </td>

                        <td class="text-right">
                            300
                        </td>

                        <td class="text-right">
                            5600
                        </td>
                    </tr>

                    <tr class="fw-bold">
                        <td colspan="6"></td>

                        <td class="text-right">
                            300
                        </td>

                        <td class="text-right">
                            200
                        </td>

                        <td class="text-right">
                            100
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-primary">
                Adicionar item
            </button>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    readonly
                    label="Peso total (kg)"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    readonly
                    type="number"
                    label="Total peças"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    readonly
                    type="number"
                    label="Qtd itens"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="Valor total bens"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="Subtotal locação"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    label="Qtd dias"
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
                    v-model="form.transporter_id"
                    :error="form.errors.transporter_id"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <Input
                    label="Endereço entrega"
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
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor transporte"
                    v-model="form.delivery_value"
                    :error="form.errors.delivery_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Desconto"
                    v-model="form.discount"
                    :error="form.errors.discount"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    label="Razão do desconto"
                    v-model="form.discount_reason"
                    :error="form.errors.discount_reason"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    readonly
                    currency
                    label="Total contrato"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    readonly
                    currency
                    label="Total contrato"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor pago"
                    v-model="form.paid_value"
                    :error="form.errors.paid_value"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="À receber"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Cédula"
                    v-model="form.bill"
                    :error="form.errors.bill"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    readonly
                    label="Troco"
                />
            </div>

            <div class="col-xs-12 col-sm-6">
                <Input
                    label="Dados cheque(s)"
                    v-model="form.check_info"
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
