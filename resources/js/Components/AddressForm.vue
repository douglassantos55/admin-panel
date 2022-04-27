<template>
    <div class="row">
        <Input
            class="col-md-4"
            label="CEP"
            v-maska="'#####-###'"
            v-model="address.postcode"
            :error="errors.postcode"
            @blur="fetch"
        />

        <Input
            class="col-md-8"
            label="Endereco"
            v-model="address.street"
            :error="errors.street"
            :disabled="loading"
        />
    </div>

    <div class="row">
        <Input
            ref="numberInput"
            class="col-md-3"
            label="Numero"
            v-model="address.number"
            :disabled="loading"
        />

        <Input
            class="col-md-4"
            label="Complemento"
            v-model="address.complement"
            :disabled="loading"
        />

        <Input
            class="col-md-5"
            label="Bairro"
            v-model="address.neighborhood"
            :disabled="loading"
        />
    </div>

    <div class="row">
        <Input
            class="col-md-8"
            label="Cidade"
            :disabled="loading"
            v-model="address.city"
        />

        <Select
            class="col-md-4"
            label="UF"
            :disabled="loading"
            v-model="address.state"
            :error="errors.state"
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
    emits: ['update:address', 'update:errors'],
    components: {
        Input,
        Select,
    },
    data() {
        return {
            loading: false,
        }
    },
    props: {
        address: {
            type: Object,
            required: true,
        },
        errors: {
            type: Object,
            required: true,
        },
    },
    methods: {
        async fetch() {
            try {
                this.loading = true
                this.$emit('update:errors', { postcode: '' })

                const cep = this.address.postcode.replace(/[^\d]/g, '')
                const result = await axios.get(`https://viacep.com.br/ws/${cep}/json`)

                if (result.data.erro) {
                    this.$emit('update:errors', { postcode: 'CEP não encontrado' })
                } else {
                    this.$emit('update:address', {
                        number: '',
                        postcode: result.data.cep,
                        street: result.data.logradouro,
                        complement: result.data.complemento,
                        neighborhood: result.data.bairro,
                        city: result.data.localidade,
                        state: result.data.uf,
                    })

                    if (this.$refs.numberInput) {
                        this.$nextTick(() => this.$refs.numberInput.focus())
                    }
                }
            } catch (error) {
                if (error.response && error.response.status === 400) {
                    this.$emit('update:errors', { postcode: 'CEP inválido' })
                } else {
                    this.$emit('update:errors', {
                        postcode: 'Ocorreu um erro, por favor tente novamente'
                    })
                }
            } finally {
                this.loading = false
            }
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
                { value: 'SP', label: 'São Paulo' },
                { value: 'SE', label: 'Sergipe' },
                { value: 'TO', label: 'Tocantins' },
            ]
        },
    },
}
</script>
