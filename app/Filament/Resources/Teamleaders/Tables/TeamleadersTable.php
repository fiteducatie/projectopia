<?php

namespace App\Filament\Resources\Teamleaders\Tables;

use App\Http\Resources\Teamleader;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TeamleadersTable
{
    protected static ?string $modelLabel = 'Teamleider';
    protected static ?string $pluralModelLabel = 'Teamleiders';

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->height(40),
                TextColumn::make('name')
                    ->label('Naam')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('summary')
                    ->label('Samenvatting')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                TextColumn::make('activities_count')
                    ->label('Activiteiten')
                    ->counts('activities')
                    ->badge()
                    ->color('success'),
                TextColumn::make('created_at')
                    ->label('Aangemaakt op')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Bekijk'),
                EditAction::make()
                    ->label('Bewerk'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
