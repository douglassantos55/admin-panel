<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rent extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'customer_id',
        'period_id',
        'start_date',
        'end_date',
        'payment_type_id',
        'payment_method_id',
        'payment_condition_id',
        'transporter_id',
        'qty_days',
        'discount',
        'paid_value',
        'bill',
        'check_info',
        'delivery_address',
        'usage_address',
    ];

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentItem::class);
    }
}
