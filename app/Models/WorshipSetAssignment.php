<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorshipSetAssignment extends Model
{
    protected $fillable = [
        'worship_set_id',
        'worship_function_id',
        'member_id',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function worshipSet(): BelongsTo
    {
        return $this->belongsTo(WorshipSet::class);
    }

    public function worshipFunction(): BelongsTo
    {
        return $this->belongsTo(WorshipFunction::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
