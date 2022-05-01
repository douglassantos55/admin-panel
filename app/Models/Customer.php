<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, HasAddress, SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $appends = ['address'];

    protected $hidden = [
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'postcode',
    ];

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
