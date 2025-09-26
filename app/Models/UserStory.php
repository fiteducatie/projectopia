<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStory extends Model
{
    protected $table = 'user_stories';

    protected $fillable = [
        'project_id',
        'user_story',
        'acceptance_criteria',
        'personas',
        'mvp',
        'priority',
        'status',
    ];

    protected $casts = [
        'user_story' => 'string',
        'acceptance_criteria' => 'array',
        'personas' => 'array',
        'mvp' => 'boolean',

    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'persona_user_stories');
    }
}
