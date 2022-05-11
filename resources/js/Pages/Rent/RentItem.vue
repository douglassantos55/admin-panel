<script setup>
import { watch, computed } from 'vue'
import { format } from '../../utils'
import Input from '../../Components/Input.vue'
import Select from '../../Components/Select.vue'

defineEmits([
    'remove-item',
])

defineProps({
    item: {
        type: Object,
        required: true,
    },
    error: {
        type: Object,
        required: false,
    },
    equipment: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <tr>
        <td>
            <button
                type="button"
                class="btn btn-sm btn-danger"
                @click="$emit('remove-item')"
                data-test="remove_item"
            >
                Remover
            </button>
        </td>

        <td>
            <Select
                required
                label=""
                :options="equipment"
                textBy="description"
                v-model="item.equipment_id"
                :error="error.equipment_id"
                placeholder="Escolha o equipamento"
            />
        </td>

        <td width="100">
            <Input
                min="1"
                label=""
                required
                type="number"
                v-model="item.qty"
                :error="error.qty"
            />
        </td>

        <td data-test="weight">
            <span v-if="item.weight">{{ format(item.weight) }}</span>
        </td>

        <td data-test="unit_value">
            <span v-if="item.unit_value">{{ format(item.unit_value) }}</span>
        </td>

        <td data-test="rent_value">
            <span v-if="item.rent_value">{{ format(item.rent_value) }}</span>
        </td>

        <td data-test="subtotal_weight">
            <span v-if="item.subtotal_weight">{{ format(item.subtotal_weight) }}</span>
        </td>

        <td data-test="subtotal_unit_value">
            <span v-if="item.subtotal_unit_value">{{ format(item.subtotal_unit_value) }}</span>
        </td>

        <td data-test="subtotal_rent_value">
            <span v-if="item.subtotal_rent_value">{{ format(item.subtotal_rent_value) }}</span>
        </td>
    </tr>
</template>
