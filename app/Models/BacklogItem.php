<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BacklogItem extends Model
{
    protected $fillable = [
        'project_id',
        'sprint_id',
        'epic',
        'title',
        'description',
        'acceptance_criteria',
        'priority',
        'effort',
        'dependencies',
        'status',
    ];

    protected $casts = [
        'dependencies' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }
}
