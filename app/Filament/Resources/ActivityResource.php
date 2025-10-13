<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Resources\ActivityResource\Factories\ActivityTabFactory;
use App\Models\Activity;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationLabel = 'Activiteiten';

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return $schema

            ->schema([
                Tabs::make('Activity Edit')
                    ->persistTabInQueryString()
                    ->columnSpanFull()
                    ->tabs(ActivityTabFactory::createFormTabs()),

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
                SelectFilter::make('domain')
                    ->options(function () {
                        return Activity::query()
                            ->distinct()
                            ->pluck('domain', 'domain')
                            ->toArray();
                    })
                    ->label('Domein'),
                SelectFilter::make('difficulty')
                    ->options(function() {
                        return Activity::query()
                            ->distinct()
                            ->pluck('difficulty', 'difficulty')
                            ->toArray();
                    })
                    ->label('Moeilijkheid'),
                SelectFilter::make('status')
                    ->options(function() {
                        return Activity::query()
                            ->distinct()
                            ->pluck('status', 'status')
                            ->toArray();
                    })
                    ->label('Status'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Bekijk'),
                EditAction::make()
                    ->label('Bewerk'),
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
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'view' => Pages\ViewActivity::route('/{record}'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}

