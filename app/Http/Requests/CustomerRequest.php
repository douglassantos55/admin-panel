<?php

namespace App\Http\Requests;

use App\Models\Customer;
use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class CustomerRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(CpfCnpj $cpfRule)
    {
        $unique = Rule::unique(Customer::class);

        if ($this->route('customer')) {
            $unique->ignore($this->route('customer'));
        }

        return [
            'name' => ['required'],
            'email' => ['nullable', 'email'],
            'birthdate' => ['nullable', 'date'],
            'cpf_cnpj' => ['required', $cpfRule, $unique],
            'phone' => ['nullable', 'regex:/^\(\d{2}\) \d{4}-\d{4}$/'],
            'cellphone' => ['nullable', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'address.state' => ['nullable', 'size:2'],
            'address.postcode' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],
        ];
    }

    public function authorize()
    {
        if ($this->route('customer')) {
            return Gate::check('update-customer');
        }
        return Gate::check('create-customer');
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function () {
            $this->replace([
                ...$this->except('address'),
                ...$this->post('address'),
            ]);
        });
    }
}
