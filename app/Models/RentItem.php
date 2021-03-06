<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'qty',
        'equipment_id',
    ];

    protected $appends = [
        'subtotal_weight',
        'subtotal_rent_value',
        'subtotal_unit_value',
    ];

    public static function booted()
    {
        static::saving(function ($item) {
            $item->rent_value = $item->rent_value;
            $item->unit_value = $item->equipment?->unit_value;
        });
    }

    public function subtotalWeight(): Attribute
    {
        return Attribute::get(function () {
            return $this->qty * $this->equipment?->weight;
        });
    }

    public function subtotalRentValue(): Attribute
    {
        return Attribute::get(function () {
            return $this->qty * $this->rent_value;
        });
    }

    public function subtotalUnitValue(): Attribute
    {
        return Attribute::get(fn () => $this->qty * $this->unit_value);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function rentValue(): Attribute
    {
        return Attribute::get(function ($value) {
            if (!$value) {
                $periodId = $this->rent?->period_id;
                $condition = $this->rent?->paymentCondition;

                $value = $this->equipment?->getRentingValue($periodId);
                return $value * (1 + $condition?->increment / 100);
            }
            return $value;
        });
    }

    public function unitValue(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return $this->equipment?->unit_value;
                }
                return $value;
            }
        );
    }

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }
}
