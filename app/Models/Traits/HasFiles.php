<?php

namespace App\Models\Traits;

use App\Models\File;

trait HasFiles
{
    public function files(string $collection = 'default')
    {
        return $this->morphToMany(File::class, 'fileable')
            ->wherePivot('collection', $collection)
            ->withTimestamps();
    }
} 