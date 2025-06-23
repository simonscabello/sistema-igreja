<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
