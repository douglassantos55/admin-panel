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

    public static function booted()
    {
        static::saving(function ($item) {
            $item->rent_value = $item->rent_value;
            $item->unit_value = $item->equipment?->unit_value;
        });
    }

    public function subtotalRentValue(): Attribute
    {
        return Attribute::get(fn () => $this->qty * $this->rent_value);
    }

    public function subtotalUnitValue(): Attribute
    {
        return Attribute::get(fn () => $this->qty * $this->unit_value);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function rent(): BelongsTo
    {
        return $this->belongsTo(Rent::class);
    }

    public function rentValue(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    $periodId = $this->rent?->period_id;
                    return $this->equipment?->getRentingValue($periodId);
                }
                return $value;
            }
        );
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
}
