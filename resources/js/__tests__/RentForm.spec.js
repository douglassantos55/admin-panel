import { nextTick } from 'vue'
import { mount } from '@vue/test-utils'
import Form from '../Pages/Rent/Form.vue'

describe('RentForm', () => {
    it('filters payment conditions based on selected payment type', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [],
                payment_types: [
                    { id: 1, name: 'A vista' },
                    { id: 2, name: 'Faturado' },
                ],
                payment_conditions: [
                    { id: 1, payment_type_id: 1, name: 'Vista inicio' },
                    { id: 2, payment_type_id: 1, name: 'Vista 7 dias' },
                    { id: 3, payment_type_id: 2, name: '28 dias' },
                    { id: 4, payment_type_id: 2, name: 'Ent 28 dias' },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="tipo_pagamento"]').setValue(1)
        const paymentCondition = form.get('[data-test="condicao_pagamento"]')

        expect(paymentCondition.find('[value="1"]').exists()).toBe(true)
        expect(paymentCondition.find('[value="2"]').exists()).toBe(true)
        expect(paymentCondition.find('[value="3"]').exists()).toBe(false)
        expect(paymentCondition.find('[value="4"]').exists()).toBe(false)
    })

    it('adds selected period duration to start date', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario', qty_days: 1 },
                    { id: 2, name: 'Semanal', qty_days: 7 },
                ],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="periodo"]').setValue(2)
        await form.get('[data-test="data_inicio"]').setValue('2022-05-10')

        expect(form.get('[data-test="data_termino"]').element.value).toBe('2022-05-17')
    })

    it('duplicates start hour on end hour', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="hora_inicio"]').setValue('08:30')
        expect(form.get('[data-test="hora_termino"]').element.value).toBe('08:30')
    })

    it('fills qty days with selected period\`s qty days', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario', qty_days: 1 },
                    { id: 2, name: 'Semanal', qty_days: 7 },
                ],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="periodo"]').setValue(2)
        expect(form.get('[data-test="qtd_dias"]').element.value).toBe('7')
    })

    it('disables Add item button if no condition is selected', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [],
                payment_types: [
                    { id: 1, name: 'A vista' },
                ],
                payment_conditions: [
                    { id: 1, payment_type_id: 1, name: 'Vista inicio' },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        expect(form.get('[data-test="add_item"]').attributes()).toHaveProperty('disabled')

        await form.get('[data-test="tipo_pagamento"').setValue(1)
        await form.get('[data-test="condicao_pagamento"').setValue(1)

        expect(form.get('[data-test="add_item"]').attributes()).not.toHaveProperty('disabled')
    })

    it('adds items', async () => {
        const form = mount(Form, {
            props: {
                customers: [],
                periods: [],
                payment_types: [
                    { id: 1, name: 'A vista' },
                ],
                payment_conditions: [
                    { id: 1, payment_type_id: 1, name: 'Vista inicio' },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="tipo_pagamento"').setValue(1)
        await form.get('[data-test="condicao_pagamento"').setValue(1)

        await form.get('[data-test="add_item"]').trigger('click')
        expect(form.get('[data-test="remove_item"]').exists()).toBe(true)
    })

    it('removes items', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [
                    { id: 1, name: 'A vista' },
                ],
                payment_conditions: [
                    { id: 1, payment_type_id: 1, name: 'Vista inicio' },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="tipo_pagamento"').setValue(1)
        await form.get('[data-test="condicao_pagamento"').setValue(1)

        await form.get('[data-test="add_item"]').trigger('click')
        await form.get('[data-test="remove_item"]').trigger('click')

        expect(() => form.get('tbody > tr > button')).toThrowError()
    })

    it('calculates subtotals', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [
                    {
                        id: 1,
                        description: 'Andaime',
                        weight: '0.8',
                        unit_value: '1000.00',
                        values: [
                            { period_id: 1, value: '0.55' },
                        ],
                    },
                    {
                        id: 2,
                        description: 'Escora',
                        weight: '1.0',
                        unit_value: '2000.00',
                        values: [
                            { period_id: 1, value: '0.25' },
                        ],
                    },
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 5 },
                        { equipment_id: 2, qty: 5 },
                    ]
                },
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)

        expect(form.get('[data-test="subtotal_weight_items"]').text()).toBe('9,00')
        expect(form.get('[data-test="subtotal_unit_value_items"]').text()).toBe('15.000,00')
        expect(form.get('[data-test="subtotal_rent_value_items"]').text()).toBe('4,00')
    })

    it('considers selected condition for prices', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [
                    { id: 1, name: 'A vista' },
                    { id: 2, name: 'Faturado' },
                ],
                payment_conditions: [
                    { id: 1, name: '28 dias', increment: '5.0', payment_type_id: 1 },
                    { id: 2, name: 'Ent 28 dias', increment: '15.0', payment_type_id: 2 },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [
                    {
                        id: 1,
                        description: 'Andaime',
                        weight: '0.8',
                        unit_value: '1000.00',
                        values: [
                            { period_id: 1, value: '1.0' },
                        ],
                    },
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 10 },
                    ],
                },
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)
        await form.get('[data-test="tipo_pagamento"]').setValue(2)
        await form.get('[data-test="condicao_pagamento"]').setValue(2)

        expect(form.get('[data-test="rent_value"]').text()).toBe('1,15');
        expect(form.get('[data-test="subtotal_rent_value"]').text()).toBe('11,50');
    })

    it('fills readonly fields', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [
                    { id: 1, name: 'A vista' },
                    { id: 2, name: 'Faturado' },
                ],
                payment_conditions: [
                    { id: 1, name: '28 dias', increment: '5.0', payment_type_id: 1 },
                    { id: 2, name: 'Ent 28 dias', increment: '15.0', payment_type_id: 2 },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [
                    {
                        id: 1,
                        description: 'Andaime',
                        weight: '0.8',
                        unit_value: '1000.00',
                        values: [
                            { period_id: 1, value: '1.0' },
                        ],
                    },
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 10 },
                    ],
                },
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)
        await form.get('[data-test="tipo_pagamento"]').setValue(2)
        await form.get('[data-test="condicao_pagamento"]').setValue(2)

        expect(form.get('[data-test="peso_total"]').element.value).toBe('8,00');
        expect(form.get('[data-test="total_pecas"]').element.value).toBe('10');
        expect(form.get('[data-test="qtd_itens"]').element.value).toBe('1');
        expect(form.get('[data-test="valor_total_bens"]').element.value).toBe('R$ 10.000,00');
        expect(form.get('[data-test="subtotal_locacao"]').element.value).toBe('R$ 11,50');
    })

    it('shows delivery address depending on transporter', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [
                    { id: 1, name: 'Locatario', delivery: 0 },
                    { id: 2, name: 'Locadora', delivery: 1 },
                ],
                equipments: [],
            },
        })

        await form.get('[data-test="transportador"]').setValue(1)
        expect(() => form.get('[data-test="endereco_entrega"]')).toThrow()

        await form.get('[data-test="transportador"]').setValue(2)
        expect(form.get('[data-test="endereco_entrega"]').exists()).toBe(true)
    })

    it('displays delivery value only when selected transporter requires delivery', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [
                    { id: 1, name: 'Locatario', delivery: 0 },
                    { id: 2, name: 'Locadora', delivery: 1 },
                ],
                equipments: [],
            },
        })

        await form.get('[data-test="transportador"]').setValue(1)
        expect(form.find('[data-test="valor_transporte"]').exists()).toBe(false)

        await form.get('[data-test="transportador"]').setValue(2)
        expect(form.get('[data-test="valor_transporte"]').exists()).toBe(true)
    })

    it('displays discount reason only when there is discount', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="desconto"]').setValue('')
        await form.get('[data-test="desconto"]').setValue('')

        expect(form.find('[data-test="razao_desconto"]').exists()).toBe(false)

        await form.get('[data-test="desconto"]').setValue('50')
        expect(form.find('[data-test="razao_desconto"]').exists()).toBe(true)
    })

    it('displays bill if paid value > 0', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="valor_pago"]').setValue('')
        await form.get('[data-test="valor_pago"]').setValue('')

        expect(form.find('[data-test="cedula"]').exists()).toBe(false)

        await form.get('[data-test="valor_pago"]').setValue('10')
        expect(form.find('[data-test="cedula"]').exists()).toBe(true)
    })

    it('displays change if bill > paid value', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [],
                transporters: [],
                equipments: [],
            },
        })

        await form.get('[data-test="valor_pago"]').setValue('10')
        await form.get('[data-test="valor_pago"]').setValue('10')

        await form.get('[data-test="cedula"]').setValue('10')
        expect(form.find('[data-test="troco"]').exists()).toBe(false)

        await form.get('[data-test="cedula"]').setValue('50')
        expect(form.find('[data-test="troco"]').exists()).toBe(true)
    })

    it('calculates total', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [],
                payment_conditions: [
                    { id: 1, name: 'A vista', increment: 0, payment_type_id: 1 },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [
                    { id: 1, values: [ { period_id: 1, value: 10 } ] }
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 10 },
                    ],
                }
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)

        await form.get('[data-test="desconto"]').setValue('10')
        await form.get('[data-test="desconto"]').setValue('10')

        expect(form.get('[data-test="total_contrato"]').element.value).toBe('R$ 90,00')
    })

    it('calculates remaining amount', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [],
                payment_conditions: [
                    { id: 1, name: 'A vista', increment: 0, payment_type_id: 1 },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [
                    { id: 1, values: [ { period_id: 1, value: 10 } ] }
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 10 },
                    ],
                }
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)

        await form.get('[data-test="valor_pago"]').setValue('10')
        await form.get('[data-test="valor_pago"]').setValue('10')

        expect(form.get('[data-test="valor_pago"]').element.value).toBe('R$ 10,00')
        expect(form.get('[data-test="a_receber"]').element.value).toBe('R$ 90,00')
    })

    it('calculates change', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [
                    { id: 1, name: 'Diario' },
                ],
                payment_types: [],
                payment_conditions: [
                    { id: 1, name: 'A vista', increment: 0, payment_type_id: 1 },
                ],
                payment_methods: [],
                transporters: [],
                equipments: [
                    { id: 1, values: [ { period_id: 1, value: 10 } ] }
                ],
                rent: {
                    items: [
                        { equipment_id: 1, qty: 10 },
                    ],
                }
            },
        })

        await form.get('[data-test="periodo"]').setValue(1)

        await form.get('[data-test="valor_pago"]').setValue('10')
        await form.get('[data-test="valor_pago"]').setValue('10')
        await form.get('[data-test="cedula"]').setValue('50')

        expect(form.get('[data-test="troco"]').element.value).toBe('R$ 40,00')
    })

    it('shows check info if payment method is check', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [],
                payment_conditions: [],
                payment_methods: [
                    { id: 1, name: 'Dinheiro' },
                    { id: 2, name: 'Cheque a vista' },
                    { id: 3, name: 'Cheque pre-datado' },
                ],
                transporters: [],
                equipments: [],
            },
        })

        expect(form.find('[data-test="dados_cheque"]').exists()).toBe(false)

        await form.get('[data-test="forma_pagamento"]').setValue(1)
        expect(form.find('[data-test="dados_cheque"]').exists()).toBe(false)

        await form.get('[data-test="forma_pagamento"]').setValue(2)
        expect(form.find('[data-test="dados_cheque"]').exists()).toBe(true)

        await form.get('[data-test="forma_pagamento"]').setValue(3)
        expect(form.find('[data-test="dados_cheque"]').exists()).toBe(true)
    })

    it('resets values when things go hidden', async () => {
        const form = mount(Form, {
            global: {
                mocks: {
                    dispatch: () => {}
                },
            },
            props: {
                customers: [],
                periods: [],
                payment_types: [
                    { id: 1, name: 'A vista' },
                    { id: 2, name: 'Faturado' },
                ],
                payment_conditions: [
                    { id: 1, payment_type_id: 1 },
                    { id: 2, payment_type_id: 2 },
                ],
                payment_methods: [
                    { id: 1, name: 'Dinheiro' },
                    { id: 2, name: 'Cheque a vista' },
                    { id: 3, name: 'Cheque pre-datado' },
                ],
                transporters: [
                    { id: 1, delivery: false },
                    { id: 2, delivery: true },
                ],
                equipments: [],
            },
        })

        await form.get('[data-test="forma_pagamento"]').setValue(2)
        await form.get('[data-test="dados_cheque"]').setValue('banco: xyz')
        await form.get('[data-test="forma_pagamento"]').setValue(1)
        expect(form.vm.form.check_info).toBe('')

        await form.get('[data-test="valor_pago"]').setValue('10')
        await form.get('[data-test="cedula"]').setValue('50')
        await form.get('[data-test="valor_pago"]').setValue('')
        expect(form.vm.form.bill).toBe('')

        await form.get('[data-test="desconto"]').setValue('10')
        await form.get('[data-test="razao_desconto"]').setValue('teste')
        await form.get('[data-test="desconto"]').setValue('')
        expect(form.vm.form.discount_reason).toBe('')

        await form.get('[data-test="transportador"]').setValue('2')
        await form.get('[data-test="endereco_entrega"]').setValue('rua abc')
        await form.get('[data-test="transportador"]').setValue('1')
        expect(form.vm.form.delivery_address).toBe('')

        await form.get('[data-test="tipo_pagamento"]').setValue('1')
        await form.get('[data-test="condicao_pagamento"]').setValue('1')
        await form.get('[data-test="tipo_pagamento"]').setValue('2')
        expect(form.vm.form.payment_condition_id).toBe('')
    })
})
