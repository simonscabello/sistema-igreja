<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialSubcategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'financial_category_id',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function financialCategory()
    {
        return $this->belongsTo(FinancialCategory::class, 'financial_category_id');
    }

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'financial_subcategory_id');
    }
}
