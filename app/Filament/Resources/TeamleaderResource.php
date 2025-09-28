<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Teamleaders\Pages\CreateTeamleader;
use App\Filament\Resources\Teamleaders\Pages\EditTeamleader;
use App\Filament\Resources\Teamleaders\Pages\ListTeamleaders;
use App\Filament\Resources\Teamleaders\Pages\ViewTeamleader;
use App\Filament\Resources\Teamleaders\Schemas\TeamleaderForm;
use App\Filament\Resources\Teamleaders\Schemas\TeamleaderInfolist;
use App\Filament\Resources\Teamleaders\Tables\TeamleadersTable;
use App\Models\Teamleader;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeamleaderResource extends Resource
{
    protected static ?string $model = Teamleader::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Team Leiders';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $tenantOwnershipRelationshipName = 'team';

    public static function form(Schema $schema): Schema
    {
        return TeamleaderForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeamleaderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeamleadersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTeamleaders::route('/'),
            'create' => CreateTeamleader::route('/create'),
            'view' => ViewTeamleader::route('/{record}'),
            'edit' => EditTeamleader::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
