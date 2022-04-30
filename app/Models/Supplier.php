<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

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
