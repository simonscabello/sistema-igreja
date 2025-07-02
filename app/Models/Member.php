<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\HasFiles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Member extends Model
{
    use HasFactory, HasFiles;

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

    public function responsibleDepartments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_responsible_member')
                    ->withTimestamps();
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_member')
                    ->withTimestamps();
    }

    public function getAllDepartmentsAttribute(): Collection
    {
        return $this->responsibleDepartments->merge($this->departments);
    }

    public function foto(): MorphToMany
    {
        return $this->files('foto_perfil');
    }
}
