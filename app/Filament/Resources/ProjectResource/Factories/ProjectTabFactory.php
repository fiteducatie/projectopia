<?php

namespace App\Filament\Resources\ProjectResource\Factories;

use App\Filament\Resources\ProjectResource\Config\ProjectTabsConfig;
use App\Filament\Resources\ProjectResource\Tabs\OverviewTab;
use App\Filament\Resources\ProjectResource\Tabs\UserStoriesTab;
use App\Filament\Resources\ProjectResource\Tabs\PersonasTab;
use App\Filament\Resources\ProjectResource\Tabs\AttachmentsTab;
use App\Filament\Resources\ProjectResource\Tabs\TeamLeadersTab;
use App\Filament\Resources\ProjectResource\Tabs\ScheduleTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\OverviewInfolistTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\UserStoriesInfolistTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\PersonasInfolistTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\AttachmentsInfolistTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\TeamLeadersInfolistTab;
use App\Filament\Resources\ProjectResource\InfolistTabs\ScheduleInfolistTab;

class ProjectTabFactory
{
    /**
     * Create form tabs for ProjectResource
     */
    public static function createFormTabs(): array
    {
        $tabs = [];
        $config = ProjectTabsConfig::getTabConfiguration();

        foreach ($config as $tabKey => $tabConfig) {
            $tab = \Filament\Schemas\Components\Tabs\Tab::make($tabConfig['name'])
                ->icon($tabConfig['icon']);

            if ($tabConfig['badge']) {
                $tab->badge($tabConfig['badge']);
            }

            $tab->schema(match ($tabKey) {
                'overview' => OverviewTab::make(),
                'user_stories' => UserStoriesTab::make(),
                'personas' => PersonasTab::make(),
                'attachments' => AttachmentsTab::make(),
                'team_leaders' => TeamLeadersTab::make(),
                'schedule' => ScheduleTab::make(),
                default => [],
            });

            $tabs[] = $tab;
        }

        return $tabs;
    }

    /**
     * Create infolist tabs for ViewProject
     */
    public static function createInfolistTabs(): array
    {
        $tabs = [];
        $config = ProjectTabsConfig::getTabConfiguration();

        foreach ($config as $tabKey => $tabConfig) {
            $tab = \Filament\Schemas\Components\Tabs\Tab::make($tabConfig['name'])
                ->icon($tabConfig['icon']);

            if ($tabConfig['badge']) {
                $tab->badge($tabConfig['badge']);
            }

            $tab->schema(match ($tabKey) {
                'overview' => OverviewInfolistTab::make(),
                'user_stories' => UserStoriesInfolistTab::make(),
                'personas' => PersonasInfolistTab::make(),
                'attachments' => AttachmentsInfolistTab::make(),
                'team_leaders' => TeamLeadersInfolistTab::make(),
                'schedule' => ScheduleInfolistTab::make(),
                default => [],
            });

            $tabs[] = $tab;
        }

        return $tabs;
    }
}
