<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'goal_amount',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function transactions()
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function getProgressAttribute()
    {
        return $this->transactions()->where('type', 'entrada')->sum('amount');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->goal_amount <= 0) {
            return 0;
        }
        
        $progress = $this->progress;
        return min(100, ($progress / $this->goal_amount) * 100);
    }
}
