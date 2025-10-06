<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'slug',
        'schedule',
        'domain',
        'context',
        'objectives',
        'constraints',
        'start_date',
        'end_date',
        'risk_notes',
        'difficulty',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'schedule' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = \Illuminate\Support\Str::slug($project->name);
            }
        });

        static::updating(function ($project) {
            if ($project->isDirty('name') && empty($project->slug)) {
                $project->slug = \Illuminate\Support\Str::slug($project->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function teamleaders(): BelongsToMany
    {
        return $this->belongsToMany(Teamleader::class);
    }

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class);
    }

    public function userStories(): HasMany
    {
        return $this->hasMany(UserStory::class);
    }

    public function getAttachmentMetadata(): array
    {
        $attachments = $this->getMedia('attachments');
        $result = [];

        foreach ($attachments as $media) {
            $customProperties = $media->custom_properties ?? [];
            $personaIds = $customProperties['persona_ids'] ?? [];
            $personaNames = [];

            if (!empty($personaIds)) {
                $personaNames = $this->personas()->whereIn('id', $personaIds)->pluck('name')->toArray();
            }

            $result[] = [
                'media_id' => $media->id,
                'file_name' => $media->file_name,
                'name' => $customProperties['name'] ?? $media->name,
                'description' => $customProperties['description'] ?? '',
                'persona_ids' => $personaIds,
                'persona_names' => $personaNames,
                'url' => $media->getUrl(),
                'size' => $media->size,
                'mime_type' => $media->mime_type,
            ];
        }

        return $result;
    }

    public function updateAttachmentMetadata(int $mediaId, array $metadata): void
    {
        $media = $this->getMedia('attachments')->find($mediaId);
        if ($media) {
            $customProperties = $media->custom_properties ?? [];
            $customProperties['name'] = $metadata['name'] ?? $media->name;
            $customProperties['description'] = $metadata['description'] ?? '';
            $customProperties['persona_ids'] = $metadata['persona_ids'] ?? [];

            $media->custom_properties = $customProperties;
            $media->name = $metadata['name'] ?? $media->name;
            $media->save();
        }
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('attachments');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(600)
            ->sharpen(10)
            ->performOnCollections('attachments');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
            ])
            ->singleFile();

        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp',
                'application/pdf',
                'application/octet-stream', // For PDFs that are detected as octet-stream
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel',
                'video/mp4',
                'video/avi',
                'video/mov',
                'video/quicktime',
                'text/plain',
                'application/zip',
                'application/x-zip-compressed',
                'application/x-zip'
            ]);
    }

}
