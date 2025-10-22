<?php

namespace App\Filament\Resources\ActivityResource\Factories;

use App\Filament\Resources\ActivityResource\Config\ActivityTabsConfig;
use App\Filament\Resources\ActivityResource\Tabs\OverviewTab;
use App\Filament\Resources\ActivityResource\Tabs\UserStoriesTab;
use App\Filament\Resources\ActivityResource\Tabs\PersonasTab;
use App\Filament\Resources\ActivityResource\Tabs\AttachmentsTab;
use App\Filament\Resources\ActivityResource\Tabs\TeamLeadersTab;
use App\Filament\Resources\ActivityResource\Tabs\ScheduleTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\OverviewInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\UserStoriesInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\PersonasInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\AttachmentsInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\InfoPopupInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\TeamLeadersInfolistTab;
use App\Filament\Resources\ActivityResource\InfolistTabs\ScheduleInfolistTab;
use App\Filament\Resources\ActivityResource\Tabs\InfoPopupTab;

class ActivityTabFactory
{
    /**
     * Create form tabs for ActivityResource
     */
    public static function createFormTabs(): array
    {
        $tabs = [];
        $config = ActivityTabsConfig::getTabConfiguration();

        foreach ($config as $tabKey => $tabConfig) {
            $tab = \Filament\Schemas\Components\Tabs\Tab::make($tabConfig['name'])
                ->icon($tabConfig['icon']);

            if ($tabConfig['badge']) {
                $tab->badge($tabConfig['badge']);
            }

            $tab->schema(match ($tabKey) {
                'overview' => OverviewTab::make(),
                'info_popup' => InfoPopupTab::make(),
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
     * Create infolist tabs for ViewActivity
     */
    public static function createInfolistTabs(): array
    {
        $tabs = [];
        $config = ActivityTabsConfig::getTabConfiguration();

        foreach ($config as $tabKey => $tabConfig) {
            $tab = \Filament\Schemas\Components\Tabs\Tab::make($tabConfig['name'])
                ->icon($tabConfig['icon']);

            if ($tabConfig['badge']) {
                $tab->badge($tabConfig['badge']);
            }

            $tab->schema(match ($tabKey) {
                'overview' => OverviewInfolistTab::make(),
                'info_popup' => InfoPopupInfolistTab::make(),
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

