<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function responsibleMembers(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'department_responsible_member')
                    ->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'department_member')
                    ->withTimestamps();
    }

    public function getAllMembersAttribute(): Collection
    {
        return $this->responsibleMembers->merge($this->members);
    }

    public function getMembersCountAttribute(): int
    {
        return $this->responsibleMembers->count() + $this->members->count();
    }
}
