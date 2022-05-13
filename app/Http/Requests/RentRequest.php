<?php

namespace App\Http\Requests;

use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class RentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->route('rent')) {
            return Gate::check('update-rent');
        }
        return Gate::check('create-rent');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'exists:App\Models\Customer,id'],
            'period_id' => ['required', 'exists:App\Models\Period,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'payment_type_id' => ['required', 'exists:App\Models\PaymentType,id'],
            'payment_method_id' => ['required', 'exists:App\Models\PaymentMethod,id'],
            'payment_condition_id' => ['required', 'exists:App\Models\PaymentCondition,id'],
            'transporter_id' => ['required', 'exists:App\Models\Transporter,id'],
            'qty_days' => ['required', 'integer'],
            'discount' => ['nullable', 'numeric'],
            'paid_value' => ['nullable', 'numeric'],
            'bill' => [
                'nullable',
                Rule::requiredIf(fn () => $this->input('paid_value') > 0),
                'numeric'
            ],
            'check_info' => [
                'nullable',
                Rule::requiredIf(function () {
                    $paymentMethod = PaymentMethod::find($this->input('payment_method_id'));
                    if (!$paymentMethod) {
                        return false;
                    }
                    return str($paymentMethod->name)->contains('Cheque');
                }),
            ],
            'items' => ['required', 'min:1'],
            'items.*.equipment_id' => ['required', 'exists:App\Models\Equipment,id'],
            'items.*.qty' => ['required', 'integer', 'numeric', 'gte:1'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function () {
            $startDate = $this->input('start_date');
            $startHour = $this->input('start_hour');

            $endDate = $this->input('end_date');
            $endHour = $this->input('end_hour');

            $this->merge([
                'start_date' => Carbon::createFromFormat('Y-m-d H:i', "{$startDate} {$startHour}"),
                'end_date' => Carbon::createFromFormat('Y-m-d H:i', "{$endDate} {$endHour}"),
            ]);
        });
    }
}
