<script setup>
import { watch } from 'vue'
import { units } from '../../utils'
import { Link } from '@inertiajs/inertia-vue3'
import useForm from '../../Composables/useForm'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'
import DecimalInput from '../../Components/DecimalInput.vue'

const props = defineProps({
    equipment: {
        type: Object,
        required: false,
    },
    suppliers: {
        type: Array,
        required: true,
    },
    periods: {
        type: Array,
        required: true,
    },
})

function getIndex(periodId) {
    return form.values.findIndex(({ period_id }) => period_id === periodId)
}

const { form, submit } = useForm(props.equipment || {
    unit: '',
    description: '',
    supplier_id: '',
    profit_percentage: null,
    in_stock: null,
    weight: null,
    effective_qty: null,
    min_qty: null,
    purchase_value: null,
    unit_value: null,
    replace_value: null,
    values: props.periods.map(period => ({ period_id: period.id, value: null })),
})

watch(() => props.equipment, equipment => {
    if (equipment) {
        for (const period of props.periods) {
            if (getIndex(period.id) == -1) {
                form.values.push({ period_id : period.id, value: null })
            }
        }
    }
}, { deep: true })

watch(() => [form.purchase_value, form.profit_percentage], (data, prev) => {
    if (data[0] != prev[0] || data[1] != prev[1]) {
        form.unit_value = form.purchase_value * (1 + form.profit_percentage / 100)
        form.replace_value = form.unit_value * (1 + form.profit_percentage / 100)
    }
})
</script>

<template>
    <h1 class="mb-4">{{ equipment ? 'Editar' : 'Cadastrar' }} equipamento</h1>

    <form @submit.prevent="submit('equipments')">
        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <Select
                    label="Unidade"
                    v-model="form.unit"
                    :error="form.errors.unit"
                    :options="units"
                />
            </div>

            <div class="col-xs-12 col-sm-5">
                <Input
                    required
                    label="Descrição"
                    v-model="form.description"
                    :error="form.errors.description"
                />
            </div>

            <div class="col-xs-12 col-sm-5">
                <Select
                    label="Fornecedor"
                    v-model="form.supplier_id"
                    :error="form.errors.supplier_id"
                    :options="suppliers"
                    textBy="social_name"
                    placeholder="Produção própria"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    label="% lucro"
                    v-model.number="form.profit_percentage"
                    :error="form.errors.profit_percentage"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    label="Peso (kg)"
                    v-model="form.weight"
                    :error="form.errors.weight"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    min="0"
                    type="number"
                    label="Qtd estoque"
                    v-model="form.in_stock"
                    :error="form.errors.in_stock"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    min="0"
                    type="number"
                    label="Qtd efetiva"
                    v-model="form.effective_qty"
                    :error="form.errors.effective_qty"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <Input
                    min="0"
                    type="number"
                    label="Qtd mínima"
                    v-model="form.min_qty"
                    :error="form.errors.min_qty"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor compra"
                    v-model.number="form.purchase_value"
                    :error="form.errors.purchase_value"
                />
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor unitário"
                    v-model="form.unit_value"
                    :error="form.errors.unit_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2">
                <DecimalInput
                    currency
                    label="Valor reposição"
                    v-model="form.replace_value"
                    :error="form.errors.replace_value"
                />
            </div>

            <div class="col-xs-12 col-sm-2" v-for="period in periods" :key="period.id">
                <DecimalInput
                    currency
                    v-if="getIndex(period.id) != -1"
                    :label="`Valor ${period.name}`"
                    v-model="form.values[getIndex(period.id)].value"
                    :error="form.errors[`values.${getIndex(period.id)}.value`]"
                />
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between">
            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                {{ equipment ? 'Editar' : 'Cadastrar' }}
            </button>

            <Link class="btn btn-secondary" :href="route('equipments.index')">
                Voltar
            </Link>
        </div>
    </form>
</template>
