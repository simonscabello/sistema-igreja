<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorshipSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'period',
        'order_notes',
        'observations',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class, 'song_worship_set')
            ->withPivot('order', 'key_used')
            ->withTimestamps()
            ->orderBy('pivot_order');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WorshipSetAssignment::class);
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->period === 'manha' ? 'Manhã' : 'Noite';
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y');
    }
}
