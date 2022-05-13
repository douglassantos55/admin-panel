<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rent extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $casts = [
        'discount' => 'float',
        'paid_value' => 'float',
        'delivery_value' => 'float',
        'bill' => 'float',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'number',
        'total',
        'change',
        'remaining',
        'total_pieces',
        'total_weight',
        'total_rent_value',
        'total_unit_value',
    ];

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
        'observations',
        'check_info',
        'delivery_value',
        'delivery_address',
        'usage_address',
    ];

    public function number(): Attribute
    {
        return Attribute::get(function () {
            return str_pad($this->attributes['id'], 6, '0', STR_PAD_LEFT);
        });
    }

    public function totalWeight(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->items->reduce(function ($carry, $item) {
                    return $carry + $item->subtotal_weight;
                }, 0);
            },
            set: function ($value) {
                return $value;
            },
        );
    }

    public function totalPieces(): Attribute
    {
        return Attribute::get(function () {
            return $this->items->reduce(function ($carry, $item) {
                return $carry + $item->qty;
            });
        });
    }

    public function totalUnitValue(): Attribute
    {
        return Attribute::get(function () {
            return $this->items->reduce(function ($carry, $item) {
                return $carry + $item->subtotal_unit_value;
            });
        });
    }

    public function totalRentValue(): Attribute
    {
        return Attribute::get(function () {
            return $this->items->reduce(function ($carry, $item) {
                return $carry + $item->subtotal_rent_value;
            });
        });
    }

    public function total(): Attribute
    {
        return Attribute::get(function () {
            return $this->total_rent_value - $this->discount + $this->delivery_value;
        });
    }

    public function change(): Attribute
    {
        return Attribute::get(function () {
            return $this->bill - $this->paid_value;
        });
    }

    public function remaining(): Attribute
    {
        return Attribute::get(function () {
            return $this->total - $this->paid_value;
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function paymentCondition(): BelongsTo
    {
        return $this->belongsTo(PaymentCondition::class);
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function transporter(): BelongsTo
    {
        return $this->belongsTo(Transporter::class);
    }
}
