<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\Resources\ProjectResource\Tabs\OverviewTab;
use App\Filament\Resources\ProjectResource\Tabs\UserStoriesTab;
use App\Filament\Resources\ProjectResource\Tabs\PersonasTab;
use App\Filament\Resources\ProjectResource\Tabs\AttachmentsTab;
use App\Filament\Resources\ProjectResource\Tabs\TeamLeadersTab;
use App\Models\Project;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Projecten';

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return $schema

            ->schema([
                Tabs::make('Project Edit')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Overzicht')
                            ->icon('heroicon-o-eye')
                            ->schema(OverviewTab::make()),

                        Tabs\Tab::make('User Stories')
                            ->icon('heroicon-o-document-text')
                            ->badge(fn ($record) => $record ? $record->userStories()->count() : 0)
                            ->schema(UserStoriesTab::make()),

                        Tabs\Tab::make('Persona\'s')
                            ->icon('heroicon-o-users')
                            ->badge(fn ($record) => $record ? $record->personas()->count() : 0)
                            ->schema(PersonasTab::make()),

                        Tabs\Tab::make('Bijlagen')
                            ->icon('heroicon-o-paper-clip')
                            ->badge(fn ($record) => $record ? $record->getMedia('attachments')->count() : 0)
                            ->schema(AttachmentsTab::make()),

                        Tabs\Tab::make('Team Leiders')
                            ->icon('heroicon-o-user-group')
                            ->badge(fn ($record) => $record ? $record->teamLeaders()->count() : 0)
                            ->schema(TeamLeadersTab::make()),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('domain')->label('Domein')->badge(),
                Tables\Columns\TextColumn::make('difficulty')->label('Moeilijkheid')->badge(),
                TextColumn::make('attachments')->label('Bijlagen')->getStateUsing(function ($record) {
                    return $record->media()->count();
                }),
                Tables\Columns\TextColumn::make('status')->label('Status')
                        ->badge(fn($record) => $record->status)
                        ->colors([
                            'success' => 'open',
                            'danger' => 'closed',
                        ])
                        ->sortable()
                        ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->recordUrl(fn($record) => !$record->trashed() ? static::getUrl('view', ['record' => $record]) : null);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $tenant = Filament::getTenant();
        if ($tenant) {
            $query->where('team_id', $tenant->getKey());
        }
        return $query->withoutGlobalScopes();
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }
}
