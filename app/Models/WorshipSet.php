<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorshipSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'singer',
        'preacher',
        'used_keys',
        'date',
        'period',
        'order_notes',
        'observations',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function songs()
    {
        return $this->belongsToMany(Song::class, 'song_worship_set')
                    ->withPivot('order', 'key_used')
                    ->withTimestamps()
                    ->orderBy('pivot_order');
    }

    public function getPeriodLabelAttribute()
    {
        return $this->period === 'manha' ? 'Manhã' : 'Noite';
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }
} 