<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorshipFunction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'member_worship_function')
            ->withTimestamps()
            ->orderBy('full_name');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(WorshipSetAssignment::class);
    }

    public function getMembersCountAttribute(): int
    {
        return $this->members->count();
    }
}
