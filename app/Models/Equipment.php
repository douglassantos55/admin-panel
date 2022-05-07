<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'description',
        'unit',
        'profit_percentage',
        'weight',
        'in_stock',
        'effective_qty',
        'min_qty',
        'purchase_value',
        'unit_value',
        'replace_value',
        'supplier_id',
    ];

    public function getRentingValue($periodId): ?float
    {
        $value = $this->values->first(
            fn ($value) =>
            $value->period_id == $periodId
        );

        return $value?->value;
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(RentingValue::class, 'equipment_id');
    }
}
