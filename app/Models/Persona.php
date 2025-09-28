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

    public function userStories()
    {
        return $this->belongsToMany(UserStory::class, 'persona_user_stories');
    }

    public function attachments()
    {
        return $this->project
            ->media()
            ->whereJsonContains('custom_properties->persona_ids', $this->id);
    }
}
