import { nextTick } from 'vue'
import Form from '../Pages/Equipment/Form.vue'
import { mount } from '@vue/test-utils'

describe('EquipmentForm', () => {
    it('calculates unit value', async () => {
        const form = mount(Form, {
            props: {
                suppliers: [],
                periods: [],
            },
            global: {
                mocks: {
                    route: function() {}
                },
            },
        })

        // doesn't work without this for some reason
        await form.get('[id*="valor_compra"]').setValue('100')

        await form.get('[id*="_lucro"]').setValue('10')
        await form.get('[id*="valor_compra"]').setValue('100')

        expect(form.find('[id*="_lucro"]').element.value).toBe('10,00')
        expect(form.find('[id*="valor_unit"]').element.value).toBe('R$ 110,00')
        expect(form.find('[id*="valor_repo"]').element.value).toBe('R$ 121,00')
    })
})
