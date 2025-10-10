<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStory extends Model
{
    protected $table = 'user_stories';

    protected $fillable = [
        'activity_id',
        'user_story',
        'acceptance_criteria',
        'personas',
        'mvp',
        'priority',
        'status',
    ];

    protected $casts = [
        'user_story' => 'string',
        'acceptance_criteria' => 'json',
        'personas' => 'json',
        'mvp' => 'boolean',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    // Custom accessors to handle double-encoded JSON
    public function getAcceptanceCriteriaAttribute($value)
    {
        if (is_string($value)) {
            // Handle double-encoded JSON
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                // It's still a JSON string, decode again
                $decoded = json_decode($decoded, true);
            }
            return is_array($decoded) ? $decoded : [$value];
        }
        return $value;
    }

    public function getPersonasAttribute($value)
    {
        if (is_string($value)) {
            // Handle double-encoded JSON
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                // It's still a JSON string, decode again
                $decoded = json_decode($decoded, true);
            }
            return is_array($decoded) ? $decoded : [$value];
        }
        return $value;
    }

    // Note: personas are stored as JSON in the personas column, not as a relationship
    // The relationship method is commented out to avoid conflicts with the JSON column
    // public function personas()
    // {
    //     return $this->belongsToMany(Persona::class, 'persona_user_stories');
    // }
}
