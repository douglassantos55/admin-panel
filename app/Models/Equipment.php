<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Equipment extends Model
{
    use HasFactory;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(RentingValue::class, 'equipment_id');
    }
}
