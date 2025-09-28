<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Dom\Text;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Schemas\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Components\CodeEntry;
use Phiki\Grammar\Grammar;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Bewerken'),
            Action::make('view-guest')
                ->label('Bekijk als gast')
                ->color('gray')
                ->url(fn () => route('choose.project', $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make()
                ->label('Verwijderen')
                ->color('danger')
                ->visible(fn () => !$this->record->trashed()),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Project Details')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Overzicht')
                            ->icon('heroicon-o-eye')
                            ->schema([
                                ImageEntry::make('banner')
                                    ->label('')
                                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('banner'))
                                    ->height(200)
                                    ->columnSpanFull()
                                    ->visible(fn ($record) => $record->getFirstMediaUrl('banner') !== null),

                                Grid::make(4)
                                    ->schema([
                                        TextEntry::make('name')->label('Naam')->weight('bold')->size('xl')->columnSpan(2),
                                TextEntry::make('domain')->label('Domein')->badge()->columnSpan(1),
                                TextEntry::make('difficulty')->label('Moeilijkheid')->badge()->color('warning')->columnSpan(1),
                                        TextEntry::make('status')->label('Status')->badge()->color(fn($state) => $state === 'open' ? 'success' : 'danger')->columnSpan(2),
                            ])->columnSpanFull(),

                                InfoSection::make('Context')
                            ->schema([
                                        TextEntry::make('context')->markdown()->hiddenLabel(),
                                    ])->collapsible(),

                        InfoSection::make('Doelstellingen')
                            ->schema([
                                        TextEntry::make('objectives')->markdown()->hiddenLabel(),
                            ])->collapsible(),

                        InfoSection::make('Randvoorwaarden')
                            ->schema([
                                        TextEntry::make('constraints')->markdown()->hiddenLabel(),
                            ])->collapsible(),
                        ]),

                        Tabs\Tab::make('User Stories')
                            ->icon('heroicon-o-document-text')
                            ->badge(fn () => $this->record->userStories()->count())
                            ->schema([
                        RepeatableEntry::make('userStories')
                                    ->label('')
                            ->schema([
                                TextEntry::make('user_story')->label('User Story')->weight('semibold')->columnSpanFull()
                                    ->limitList(1),
                                TextEntry::make('acceptance_criteria')->label('Acceptatiecriteria')
                                    ->listWithLineBreaks()
                                            ->formatStateUsing(function ($state) {
                                                return collect($state)->map(function ($criteria) {
                                                    return '✔️ ' . $criteria;
                                                })->implode("\n");
                                            }),
                                TextEntry::make('mvp')
                                    ->badge()
                                            ->hiddenLabel()
                                    ->formatStateUsing(fn ($state) => $state ? 'MVP' : 'NTH')
                                    ->color(fn ($state) => $state ? 'success' : 'secondary')
                                    ->columnSpan(1),
                                TextEntry::make('priority')->label('Prioriteit')->badge()->color(fn ($state) => match ($state) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    'low' => 'success',
                                    default => 'secondary',
                                })->columnSpan(1),
                            ])
                            ]),

                        Tabs\Tab::make('Persona\'s')
                            ->icon('heroicon-o-users')
                            ->badge(fn () => $this->record->personas()->count())
                            ->schema([
                                RepeatableEntry::make('personas')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                ImageEntry::make('avatar_url')->circular()->height(56)->columnSpan(1),
                                                Grid::make(1)
                                                    ->schema([
                                                        TextEntry::make('role')->label('Rol')->weight('semibold'),
                                                        TextEntry::make('name')->label('Naam'),
                                                    ])->columnSpan(1),
                                            ]),

                                        TextEntry::make('goals')->label('Doelen')->columnSpanFull(),
                                        TextEntry::make('traits')->label('Eigenschappen')->columnSpanFull(),
                                        TextEntry::make('communication_style')->label('Communicatiestijl')->columnSpanFull(),

                                        // Bestanden sectie per persona
                                        TextEntry::make('attachments_list')
                                            ->label('Kennis / in bezit van de volgende bestanden')
                                            ->getStateUsing(function ($record) {
                                                $attachments = $record->attachments()->get();

                                                if ($attachments->isEmpty()) {
                                                    return 'Geen bestanden toegewezen';
                                                }

                                                return $attachments->map(function ($media) {
                                                    $customProps = $media->custom_properties ?? [];
                                                    $name = $customProps['name'] ?? $media->name;
                                                    $description = $customProps['description'] ?? '';

                                                    $result = "📎 [**{$name}**]({$media->getUrl()})";
                                                    if ($description) {
                                                        $result .= " - *{$description}*";
                                                    }
                                                    $result .= " <small>({$media->mime_type})</small>";

                                                    return $result;
                                                })->join("\n\n");
                                            })
                                            ->markdown()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),

                        Tabs\Tab::make('Bijlagen')
                            ->icon('heroicon-o-paper-clip')
                            ->badge(fn () => $this->record->getMedia('attachments')->count())
                            ->schema([
                                RepeatableEntry::make('attachments')
                                    ->getStateUsing(fn () => $this->record->getAttachmentMetadata())
                                    ->schema([
                                        Grid::make(4)
                                            ->schema([
                                                ImageEntry::make('url')
                                                    ->label('Preview')
                                                    ->height(120)
                                                    ->width(160)
                                                    ->columnSpan(1)
                                                    ->visible(fn ($state) => str_starts_with($state, 'http')),

                                                TextEntry::make('name')
                                                    ->label('Bestandsnaam')
                                                    ->weight('semibold')
                                                    ->columnSpan(2),

                                                TextEntry::make('file_name')
                                                    ->label('Originele naam')
                                                    ->color('gray')
                                                    ->columnSpan(1),

                                                TextEntry::make('description')
                                                    ->label('Beschrijving')
                                                    ->columnSpanFull()
                                                    ->placeholder('Geen beschrijving beschikbaar')
                                                    ->color(fn ($state) => $state ? null : 'gray'),

                                                TextEntry::make('mime_type')
                                                    ->label('Type')
                                                    ->badge()
                                                    ->color('secondary')
                                                    ->columnSpan(1),

                                                TextEntry::make('size')
                                                    ->label('Grootte')
                                                    ->formatStateUsing(fn ($state) => $this->formatFileSize($state))
                                                    ->color('gray')
                                                    ->columnSpan(1),

                                                TextEntry::make('persona_names')
                                                    ->label('Relevante Persona\'s')
                                                    ->formatStateUsing(function ($state) {
                                                        if (!$state) {
                                                            return 'Geen persona\'s geselecteerd';
                                                        }

                                                        // Ensure $state is an array
                                                        if (is_string($state)) {
                                                            return $state;
                                                        }

                                                        if (is_array($state)) {
                                                            return implode(', ', $state);
                                                        }

                                                        return 'Geen persona\'s geselecteerd';
                                                    })
                                                    ->color(fn ($state) => $state ? null : 'gray')
                                                    ->columnSpan(2),
                                            ]),
                                    ])
                                    ->columns(1)
                            ]),

                        Tabs\Tab::make('Team Leiders')
                            ->icon('heroicon-o-user-group')
                            ->badge(fn () => $this->record->teamleaders()->count())
                            ->schema([
                                RepeatableEntry::make('teamleaders')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                ImageEntry::make('avatar_url')
                                                    ->label('Avatar')
                                                    ->circular()
                                                    ->columnSpan(1),
                                                Grid::make(1)
                                                    ->schema([
                                                        TextEntry::make('name')
                                                            ->label('Naam')
                                                            ->weight('bold')
                                                            ->size('lg'),
                                                        TextEntry::make('summary')
                                                            ->label('Samenvatting'),
                                                    ])
                                                    ->columnSpan(2),
                                            ]),

                                        TextEntry::make('description')
                                            ->label('Beschrijving')
                                            ->columnSpanFull(),
                                        TextEntry::make('communication_style')
                                            ->label('Communicatiestijl')
                                            ->columnSpanFull(),
                                        TextEntry::make('skillset')
                                            ->label('Vaardigheden')
                                            ->columnSpanFull(),
                                        TextEntry::make('deliverables')
                                            ->label('Verwachte Deliverables')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),
                    ])
            ]);
    }

    protected function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}


