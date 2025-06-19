<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function subcategories()
    {
        return $this->hasMany(FinancialSubcategory::class, 'financial_category_id');
    }
}
