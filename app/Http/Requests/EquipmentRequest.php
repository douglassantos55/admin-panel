<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Validator;

class EquipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('equipment')) {
            return Gate::check('update-equipment');
        }
        return Gate::check('create-equipment');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => ['required'],
            'in_stock' => ['nullable', 'integer'],
            'effective_qty' => ['nullable', 'integer'],
            'weight' => ['nullable', 'numeric'],
            'unit_value' => ['nullable', 'numeric'],
            'purchase_value' => ['nullable', 'numeric'],
            'replace_value' => ['nullable', 'numeric'],
            'min_qty' => ['nullable', 'integer'],
            'supplier_id' => ['nullable', 'exists:App\Models\Supplier,id'],
            'values.*.period_id' => ['nullable', 'exists:App\Models\Period,id'],
            'values.*.value' => ['nullable', 'numeric'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function () {
            $input = $this->input();
            if (isset($input['values'])) {
                foreach ($input['values'] as $key => $value) {
                    if (is_null($value['value'])) {
                        unset($input['values'][$key]);
                    }
                }
            }
            $this->replace($input);
        });
    }
}
