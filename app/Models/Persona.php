<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persona extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'role',
        'avatar_url',
        'goals',
        'traits',
        'communication_style',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
