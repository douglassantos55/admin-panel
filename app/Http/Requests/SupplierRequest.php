<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use App\Rules\CpfCnpj;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('supplier')) {
            return Gate::check('update-supplier');
        }
        return Gate::check('create-supplier');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(CpfCnpj $cnpjRule)
    {
        $unique = Rule::unique(Supplier::class);

        if ($this->route('supplier')) {
            $unique->ignore($this->route('supplier'));
        }

        return [
            'social_name' => ['required'],
            'email' => ['nullable', 'email'],
            'cnpj' => ['nullable', $cnpjRule, $unique],
            'phone' => ['nullable', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'address.state' => ['nullable', 'size:2'],
            'address.postcode' => ['nullable', 'regex:/^\d{5}-\d{3}$/'],

        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function () {
            $this->replace([
                ...$this->except('address'),
                ...$this->input('address'),
            ]);
        });
    }
}
