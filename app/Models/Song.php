<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'youtube_link',
        'spotify_link',
        'key',
        'lyrics_link',
        'chords_link',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function worshipSets(): BelongsToMany
    {
        return $this->belongsToMany(WorshipSet::class, 'song_worship_set')
                    ->withPivot('order', 'key_used')
                    ->withTimestamps();
    }
}
