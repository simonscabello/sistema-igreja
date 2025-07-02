<?php

namespace App\Models\Traits;

use App\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasFiles
{
    public function files(string $collection = 'default'): MorphToMany
    {
        return $this->morphToMany(File::class, 'fileable')
            ->wherePivot('collection', $collection)
            ->withTimestamps();
    }
}
