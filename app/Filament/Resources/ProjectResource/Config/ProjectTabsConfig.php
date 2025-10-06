<?php

namespace App\Filament\Resources\ProjectResource\Config;

class ProjectTabsConfig
{
    /**
     * Get the tab configuration for both form and infolist views
     */
    public static function getTabConfiguration(): array
    {
        return [
            'overview' => [
                'name' => 'Overzicht',
                'icon' => 'heroicon-o-eye',
                'badge' => null, // No count needed for overview
            ],
            'user_stories' => [
                'name' => 'User Stories',
                'icon' => 'heroicon-o-document-text',
                'badge' => fn ($record) => $record ? $record->userStories()->count() : 0,
            ],
            'personas' => [
                'name' => 'Persona\'s',
                'icon' => 'heroicon-o-users',
                'badge' => fn ($record) => $record ? $record->personas()->count() : 0,
            ],
            'attachments' => [
                'name' => 'Bijlagen',
                'icon' => 'heroicon-o-paper-clip',
                'badge' => fn ($record) => $record ? $record->getMedia('attachments')->count() : 0,
            ],
            'team_leaders' => [
                'name' => 'Teamleiders',
                'icon' => 'heroicon-o-user-group',
                'badge' => fn ($record) => $record ? $record->teamLeaders()->count() : 0,
            ],
            'schedule' => [
                'name' => 'Planning',
                'icon' => 'heroicon-o-calendar',
                'badge' => fn ($record) => $record ? count($record->schedule) : 0,
            ],
        ];
    }

    /**
     * Get all tab names for easy iteration
     */
    public static function getTabNames(): array
    {
        return array_keys(self::getTabConfiguration());
    }

    /**
     * Get tab configuration by key
     */
    public static function getTabConfig(string $tabKey): array
    {
        $config = self::getTabConfiguration();
        return $config[$tabKey] ?? [];
    }
}
