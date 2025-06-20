<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'age_group',
        'gender',
        'wants_contact',
        'visit_date',
        'notes',
        'full_address',
    ];

    protected $casts = [
        'wants_contact' => 'boolean',
        'visit_date' => 'date',
    ];
}
