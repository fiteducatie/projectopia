<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'team_id',
        'name',
        'domain',
        'context',
        'objectives',
        'constraints',
        'start_date',
        'end_date',
        'risk_notes',
        'difficulty',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class);
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }

    public function backlogItems(): HasMany
    {
        return $this->hasMany(BacklogItem::class);
    }
    public function userStories(): HasMany
    {
        return $this->hasMany(UserStory::class);
    }
}
