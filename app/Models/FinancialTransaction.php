<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_subcategory_id',
        'type',
        'amount',
        'action_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'action_date' => 'date',
    ];

    public function subcategory()
    {
        return $this->belongsTo(FinancialSubcategory::class, 'financial_subcategory_id');
    }

    public function category()
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
}
