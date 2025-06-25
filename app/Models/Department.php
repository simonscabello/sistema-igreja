<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function responsibleMembers()
    {
        return $this->belongsToMany(Member::class, 'department_responsible_member')
                    ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(Member::class, 'department_member')
                    ->withTimestamps();
    }

    public function getAllMembersAttribute()
    {
        return $this->responsibleMembers->merge($this->members);
    }

    public function getMembersCountAttribute()
    {
        return $this->responsibleMembers->count() + $this->members->count();
    }
} 