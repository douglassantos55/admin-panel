<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Rules\Libraries\ValidaCPFCNPJ;

class CpfCnpj implements Rule
{
    private $validator;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->validator = new ValidaCPFCNPJ();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->validator->valida($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'O campo :attribute nao e valido.';
    }
}
