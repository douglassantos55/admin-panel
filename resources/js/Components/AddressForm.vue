<template>
    <div class="row">
        <Input
            class="col-md-4"
            label="CEP"
            v-model="modelValue.postcode"
            :error="modelValue.errors.postcode"
            @blur="fetch"
        />

        <Input
            class="col-md-8"
            label="Endereco"
            v-model="modelValue.address"
            :error="modelValue.errors.address"
        />
    </div>

    <div class="row">
        <Input class="col-md-3" label="Numero" v-model="modelValue.number" />
        <Input class="col-md-4" label="Complemento" v-model="modelValue.neighborhood" />
        <Input class="col-md-5" label="Bairro" v-model="modelValue.complement" />
    </div>

    <div class="row">
        <Input class="col-md-8" label="Cidade" v-model="modelValue.city" />
        <Select
            class="col-md-4"
            label="UF"
            v-model="modelValue.state"
            :error="modelValue.errors.state"
            :options="states"
            key-by="value"
            text-by="label"
        />
    </div>
</template>

<script>
import axios from 'axios'
import Input from './Input.vue'
import Select from './Select.vue'

export default {
    emits: ['update:modelValue'],
    components: {
        Input,
        Select,
    },
    props: {
        modelValue: {
            type: Object,
            required: true,
        },
    },
    methods: {
        async fetch() {
            let data
            try {
                const cep = this.modelValue.postcode.replace(/[^\d]/g, '')
                const result = await axios.get(`https://viacep.com.br/ws/${cep}/json`)

                if (result.data.erro) {
                    data = { errors: { postcode: 'CEP não encontrado' } }
                } else {
                    data = {
                        address: result.data.logradouro,
                        complement: result.data.complemento,
                        neighborhood: result.data.bairro,
                        city: result.data.localidade,
                        state: result.data.uf,
                    }
                }
            } catch (error) {
                if (error.response.status === 400) {
                    data = { errors: { postcode: 'CEP inválido' } }
                } else {
                    data = {
                        errors: {
                            postcode: 'Ocorreu um erro, por favor tente novamente'
                        }
                    }
                }
            }

            this.$emit('update:modelValue', { ...this.modelValue, ...data })
        },
    },
    computed: {
        states() {
            return [
                { value: 'AC', label: 'Acre' },
                { value: 'AL', label: 'Alagoas' },
                { value: 'AP', label: 'Amapá' },
                { value: 'AM', label: 'Amazonas' },
                { value: 'BA', label: 'Bahia' },
                { value: 'CE', label: 'Ceará' },
                { value: 'DF', label: 'Distrito Federal' },
                { value: 'ES', label: 'Espírito Santo' },
                { value: 'GO', label: 'Goías' },
                { value: 'MA', label: 'Maranhão' },
                { value: 'MT', label: 'Mato Grosso' },
                { value: 'MS', label: 'Mato Grosso do Sul' },
                { value: 'MG', label: 'Minas Gerais' },
                { value: 'PA', label: 'Pará' },
                { value: 'PB', label: 'Paraíba' },
                { value: 'PR', label: 'Paraná' },
                { value: 'PE', label: 'Pernambuco' },
                { value: 'PI', label: 'Piauí' },
                { value: 'RJ', label: 'Rio de Janeiro' },
                { value: 'RN', label: 'Rio Grande do Norte' },
                { value: 'RS', label: 'Rio Grande do Sul' },
                { value: 'RO', label: 'Rondônia' },
                { value: 'RR', label: 'Roraíma' },
                { value: 'SC', label: 'Santa Catarina' },
                { value: 'SPP', label: 'São Paulo' },
                { value: 'SE', label: 'Sergipe' },
                { value: 'TO', label: 'Tocantins' },
            ]
        },
    },
}
</script>
