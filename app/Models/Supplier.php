<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
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
