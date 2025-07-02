<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_subcategory_id',
        'campaign_id',
        'type',
        'amount',
        'action_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'action_date' => 'date',
    ];

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(FinancialSubcategory::class, 'financial_subcategory_id');
    }

    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(
            FinancialCategory::class,
            FinancialSubcategory::class,
            'id',
            'id',
            'financial_subcategory_id',
            'financial_category_id'
        );
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
