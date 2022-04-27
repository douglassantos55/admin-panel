import axios from 'axios'
import { nextTick } from 'vue'
import { mount } from '@vue/test-utils'
import AddressForm from '../Components/AddressForm.vue'

describe('AddressForm', () => {
    it('fetches address on blur', async () => {
        const form = mount(AddressForm, {
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
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

        expect(form.emitted('update:address')[0][0]).toEqual({
            postcode: '01001-000',
            street: 'Praça da Sé',
            number: '',
            complement: 'lado ímpar',
            neighborhood: 'Sé',
            city: 'São Paulo',
            state: 'SP',
        })
    })

    it('displays error invalid postcode', async () => {
        const form = mount(AddressForm, {
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
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

        expect(form.emitted('update:errors')[1][0]).toEqual({
            postcode: 'CEP inválido'
        })
    })

    it('displays error not found', async () => {
        const form = mount(AddressForm, {
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
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

        expect(form.emitted('update:errors')[1][0]).toEqual({
            postcode: 'CEP não encontrado'
        })
    })

    it('displays connection error', async () => {
        const form = mount(AddressForm, {
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
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

        expect(form.emitted('update:errors')[1][0]).toEqual({
            postcode: 'Ocorreu um erro, por favor tente novamente'
        })
    })

    it('focuses number after fetching', async () => {
        const form = mount(AddressForm, {
            attachTo: document.body,
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                },
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

        const cep = form.get('[id*="cep"]')
        await cep.setValue('01001-000')
        await cep.trigger('blur')

        await nextTick()
        expect(form.find('[id*="numero"]').element).toBe(document.activeElement)
    })

    it('disables fields while fetching', async () => {
        const form = mount(AddressForm, {
            props: {
                errors: {},
                address: {
                    postcode: '',
                    street: '',
                    number: '',
                    complement: '',
                    neighborhood: '',
                    city: '',
                    state: '',
                },
            }
        })

        jest.spyOn(axios, 'get').mockImplementation(url => {
            return new Promise(res => {
                setTimeout(res({
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
                    }
                }), 100)
            })
        })

        const cep = form.get('[id*="cep"]')
        await cep.setValue('01001-000')

        cep.trigger('blur')
        await nextTick()

        expect(form.get('[id*="numero"]').attributes()).toHaveProperty('disabled')
        expect(form.get('[id*="endereco"]').attributes()).toHaveProperty('disabled')
        expect(form.get('[id*="complemento"]').attributes()).toHaveProperty('disabled')
        expect(form.get('[id*="bairro"]').attributes()).toHaveProperty('disabled')
        expect(form.get('[id*="cidade"]').attributes()).toHaveProperty('disabled')
        expect(form.get('[id*="uf"]').attributes()).toHaveProperty('disabled')
    })
})
