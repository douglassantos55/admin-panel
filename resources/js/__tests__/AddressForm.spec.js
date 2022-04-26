import axios from 'axios'
import { nextTick } from 'vue'
import { mount } from '@vue/test-utils'
import AddressForm from '../Components/AddressForm.vue'

describe('AddressForm', () => {
    it('fetches address on blur', async () => {
        const form = mount(AddressForm, {
            props: {
                modelValue: {
                    postcode: '',
                    address: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                    errors: {}
                }
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            if (url === 'https://viacep.com.br/ws/01001000/json') {
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
            }
            return Promise.reject('Invalid URL')
        })

        const cep = form.get('[id*="cep"]')

        await cep.setValue('01001-000')
        await cep.trigger('blur')

        expect(form.emitted('update:modelValue')[0][0]).toEqual({
            postcode: '01001-000',
            address: 'Praça da Sé',
            number: '',
            complement: 'lado ímpar',
            neighborhood: 'Sé',
            city: 'São Paulo',
            state: 'SP',
            errors: {}
        })
    })

    it('displays error invalid postcode', async () => {
        const form = mount(AddressForm, {
            props: {
                modelValue: {
                    postcode: '',
                    address: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                    errors: {}
                }
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            return Promise.reject({
                response: {
                    status: 400,
                }
            })
        })

        const cep = form.get('[id*="cep"]')

        await cep.setValue('950100-100')
        await cep.trigger('blur')

        expect(form.emitted('update:modelValue')[0][0]).toEqual({
            postcode: '950100-100',
            address: '',
            number: '',
            complement: '',
            neighborhood: '',
            city: '',
            state: '',
            errors: { postcode: 'CEP inválido' }
        })
    })

    it('displays error not found', async () => {
        const form = mount(AddressForm, {
            props: {
                modelValue: {
                    postcode: '',
                    address: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                    errors: {}
                }
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            return Promise.resolve({
                data: { erro: "true" },
            })
        })

        const cep = form.get('[id*="cep"]')

        await cep.setValue('99999-999')
        await cep.trigger('blur')

        expect(form.emitted('update:modelValue')[0][0]).toEqual({
            postcode: '99999-999',
            address: '',
            number: '',
            complement: '',
            neighborhood: '',
            city: '',
            state: '',
            errors: { postcode: 'CEP não encontrado' }
        })
    })

    it('displays connection error', async () => {
        const form = mount(AddressForm, {
            props: {
                modelValue: {
                    postcode: '',
                    address: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                    errors: {}
                }
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            return Promise.reject({
                response: {
                    status: 500,
                },
            })
        })

        const cep = form.get('[id*="cep"]')

        await cep.setValue('13840-310')
        await cep.trigger('blur')

        expect(form.emitted('update:modelValue')[0][0]).toEqual({
            postcode: '13840-310',
            address: '',
            number: '',
            complement: '',
            neighborhood: '',
            city: '',
            state: '',
            errors: { postcode: 'Ocorreu um erro, por favor tente novamente' }
        })

    })
})
