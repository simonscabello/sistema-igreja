<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'mobile',
        'gender',
        'marital_status',
        'birth_date',
        'baptism_date',
        'admission_date',
        'wedding_date',
        'zip_code',
        'street',
        'neighborhood',
        'city',
        'state',
        'number',
        'complement',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date',
        'admission_date' => 'date',
        'wedding_date' => 'date',
    ];
}
