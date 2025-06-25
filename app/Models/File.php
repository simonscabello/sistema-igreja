<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'original_name',
        'extension',
        'size',
        'mime_type',
        'path',
        'disk',
        'url',
    ];

    public function fileables()
    {
        return $this->morphToMany(Model::class, 'fileable');
    }
} 