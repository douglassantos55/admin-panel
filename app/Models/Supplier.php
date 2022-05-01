<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, HasAddress;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

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
        'social_name',
        'legal_name',
        'email',
        'website',
        'cnpj',
        'insc_est',
        'phone',
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
