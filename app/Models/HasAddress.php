<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasAddress
{
    public function address(): Attribute
    {
        return Attribute::get(function () {
            return [
                'postcode' => $this->postcode,
                'street' => $this->street,
                'number' => $this->number,
                'complement' => $this->complement,
                'neighborhood' => $this->neighborhood,
                'city' => $this->city,
                'state' => $this->state,
            ];
        });
    }
}
