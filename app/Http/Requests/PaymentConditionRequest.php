<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class PaymentConditionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('paymentCondition')) {
            return Gate::check('update-payment-condition');
        }
        return Gate::check('create-payment-condition');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'title' => ['required'],
            'increment' => ['nullable', 'numeric', 'min:0'],
            'payment_type_id' => ['required', 'exists:App\Models\PaymentType,id'],
            'installments.*' => ['nullable', 'integer', 'numeric', 'gte:0'],
        ];
    }
}
