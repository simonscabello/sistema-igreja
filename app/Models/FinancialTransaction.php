<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_category_id',
        'type',
        'amount',
        'action_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'action_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }
}
