<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityType extends Model
{
    protected $guarded = [];
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
