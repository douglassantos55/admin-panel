import axios from 'axios'
import { maska } from 'maska'
import { nextTick } from 'vue'
import { mount } from '@vue/test-utils'
import Form from '../Pages/Customer/Form.vue'
import { useForm } from '@inertiajs/inertia-vue3'

describe('CustomerForm', () => {
    it('fills form data on blur', async () => {
        const form = mount(Form, {
            global: {
                directives: { maska },
                mocks: { route: function() {} },
            },
            data: function() {
                return {
                    form: useForm({
                        address: {
                            postcode: '',
                            street: '',
                            number: '',
                            complement: '',
                            neighborhood: '',
                            city: '',
                            state: '',
                        },
                    }),
                }
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            return Promise.resolve({
                data: {
                    "cep": "01001-000",
                    "logradouro": "Praça da Sé",
                    "complemento": "lado ímpar",
                    "bairro": "Sé",
                    "localidade": "São Paulo",
                    "uf": "SP",
                    "ibge": "3550308",
                    "gia": "1004",
                    "ddd": "11",
                    "siafi": "7107"
                },
            })
        })

        await form.get('[id*="cep"]').setValue('01001-000')
        await form.get('[id*="cep"]').trigger('blur')

        expect(form.vm.form.address).toEqual({
            postcode: '01001-000',
            street: 'Praça da Sé',
            number: '',
            complement: 'lado ímpar',
            neighborhood: 'Sé',
            city: 'São Paulo',
            state: 'SP',
        })
    })
})
