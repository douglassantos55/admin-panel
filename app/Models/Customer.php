<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'birthdate',
        'cpf_cnpj',
        'rg_insc_est',
        'phone',
        'cellphone',
        'ocupation',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postcode',
        'observations',
    ];
}
