<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use App\Models\Persona;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Facades\Filament;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;

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
                Wizard::make([
                    Step::make('Context')
                        ->schema([
                            Section::make()
                                ->description('Beschrijf wat er gebouwd moet worden, voor wie en waarom. Dit vormt de basis voor alle beslissingen.')
                                ->schema([
                                    Forms\Components\TextInput::make('name')->label('Naam')
                                        ->helperText('Korte, herkenbare projectnaam.')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\Select::make('domain')->label('Domein')
                                        ->options([
                                            'software' => 'Software',
                                            'marketing' => 'Marketing',
                                            'event' => 'Evenement',
                                        ])->required()->default('software')
                                        ->helperText('Bepaalt de standaardtemplates (Software, Marketing, Evenement).'),
                                    Forms\Components\RichEditor::make('context')->label('Context')
                                        ->helperText('1–3 alinea’s met achtergrond, doelgroep en gewenste impact.'),
                                ]),
                        ]),
                    Step::make('Doelstellingen')
                        ->schema([
                            Section::make()
                                ->description('Welke meetbare doelen moet het project bereiken? Denk aan KPI’s of leerdoelen.')
                                ->schema([
                                    Forms\Components\RichEditor::make('objectives')->label('Doelstellingen')
                                        ->helperText('Gebruik opsommingen; maak doelen concreet en toetsbaar.'),
                                ]),
                        ]),
                    Step::make('Randvoorwaarden')
                        ->schema([
                            Section::make()
                                ->description('Beperkingen zoals tijd, budget, techniek, compliance of scope-afbakening.')
                                ->schema([
                                    Forms\Components\RichEditor::make('constraints')->label('Randvoorwaarden')
                                        ->helperText('Som de belangrijkste beperkingen op; dit helpt bij prioriteren.'),
                                ]),
                        ]),
                    Step::make('User Stories')
                        ->schema([
                            Section::make()
                                ->description('Maak de scope van het project concreet door user stories aan te leveren.')
                                ->schema([
                                    Forms\Components\Repeater::make('user_stories_data')
                                        ->relationship('userStories')
                                        ->itemLabel(fn (array $state): string => trim(substr($state['user_story'] ?? 'User Story', 0, 30) . (isset($state['user_story']) && strlen($state['user_story']) > 30 ? '...' : '')))
                                        ->label('User Stories')
                                        ->helperText('Beschrijf de functionaliteiten vanuit gebruikersperspectief. Minimaal 3 is aan te raden.')
                                        ->schema([
                                            Forms\Components\TextInput::make('user_story')->label('User Story')->required()
                                                ->helperText('Bijv. Als [rol] wil ik [doel] zodat [reden].'),
                                            Forms\Components\Repeater::make('acceptance_criteria')
                                                ->simple(
                                                    Forms\Components\TextInput::make('acceptance_criteria')->label('Acceptatie Criterium')->required(),
                                                ),
                                            Forms\Components\Select::make('personas')
                                                ->label('Persona\'s')
                                                ->multiple()
                                                ->relationship('personas', 'name')
                                                ->searchable()
                                                ->preload()
                                                //TODO: only select personas related to this project. Code beneath works at frontend, not when saved. Working on it.
                                                // ->getSearchResultsUsing(function($record){
                                                //     return Persona::where('project_id', $record->project_id ?? 0)->pluck('name', 'id')->toArray();
                                                // })
                                                // ->getOptionLabelsUsing(function ($values) {
                                                //     return Persona::whereIn('id', $values)->pluck('name')->toArray();
                                                // })
                                                ->helperText('Koppel relevante persona’s aan deze user story.'),
                                            Forms\Components\Toggle::make('mvp')->label('MVP?')
                                                ->helperText('Markeer deze user story als onderdeel van de Minimum Viable Product.'),
                                            Forms\Components\Select::make('priority')->label('Prioriteit')
                                                ->options([
                                                    'low' => 'Laag',
                                                    'medium' => 'Middel',
                                                    'high' => 'Hoog',
                                                ])->required()->default('medium')
                                                ->helperText('Helpt bij het plannen en prioriteren van werk.'),
                                        ])
                                        ->collapsed()
                                        ->minItems(1)
                                        ->grid(1),
                                ]),

                        ]),
                    Step::make('Tijdlijn')
                        ->schema([
                            Section::make()
                                ->description('Geef de planning aan. Je kunt dit later altijd aanpassen.')
                                ->schema([
                                    Forms\Components\DatePicker::make('start_date')->label('Startdatum')
                                        ->helperText('Start van Sprint 1 of project.'),
                                    Forms\Components\DatePicker::make('end_date')->label('Einddatum')
                                        ->helperText('Gewenste opleverdatum.'),
                                    Forms\Components\Select::make('difficulty')->label('Moeilijkheid')
                                        ->options([
                                            'easy' => 'Makkelijk',
                                            'normal' => 'Normaal',
                                            'hard' => 'Moeilijk',
                                        ])->default('normal')
                                        ->helperText('Indicatie voor AI-planning; heeft geen harde gevolgen.'),
                                ]),
                        ]),
                    Step::make('Persona\'s')
                        ->schema([
                            Section::make()
                                ->description('Voeg belangrijke stakeholders of doelgroepen toe. Ze worden gebruikt in de dialoogkamer.')
                                ->schema([
                                    Forms\Components\Repeater::make('personas_data')
                                        ->relationship('personas')
                                        ->itemLabel(fn (array $state): string => trim(($state['role'] ?? 'Rol') . ': ' . ($state['name'] ?? 'Naam')))
                                        ->label('Persona\'s')
                                        ->helperText('Minimaal 1 persona is aan te raden: Klant, Product Owner of Doelgroep.')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')->label('Naam')->required()
                                                ->helperText('Naam van de persona (mag fictief).'),
                                            Forms\Components\TextInput::make('role')->label('Rol')->required()
                                                ->helperText('Rol t.o.v. het project (bijv. Klant, Product Owner, Doelgroep).'),
                                            Forms\Components\TextInput::make('avatar_url')->label('Avatar-URL')->url()->nullable()
                                                ->helperText('Optioneel; laat leeg om automatisch te genereren.'),
                                            Forms\Components\TextArea::make('goals')->label('Doelen')->nullable()
                                                ->helperText('Wat wil deze persona bereiken met dit project?'),
                                            Forms\Components\TextArea::make('traits')->label('Eigenschappen')->nullable()
                                                ->helperText('Kernwoorden zoals direct, risico-avers, kwaliteitsgericht.'),
                                            Forms\Components\TextArea::make('communication_style')->label('Communicatiestijl')->nullable()
                                                ->helperText('Bijv. kort en bondig, data-gedreven, enthousiasmerend.'),
                                        ])
                                        ->collapsed()
                                        ->grid(2),
                                ]),
                        ]),
                    Step::make('Risico\'s')
                        ->schema([
                            Section::make()
                                ->description('Welke risico’s zie je nu al? Hoe kun je ze beperken?')
                                ->schema([
                                    Forms\Components\RichEditor::make('risk_notes')->label('Risico\'s')
                                        ->helperText('Beschrijf risico + mitigerende actie (bijv. capaciteit, afhankelijkheden, privacy).'),
                                ]),
                        ]),
                ])
                ->skippable()
                ->persistStepInQueryString()
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Naam')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('domain')->label('Domein')->badge(),
                Tables\Columns\TextColumn::make('start_date')->label('Startdatum')->date(),
                Tables\Columns\TextColumn::make('end_date')->label('Einddatum')->date(),
                Tables\Columns\TextColumn::make('difficulty')->label('Moeilijkheid')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Aangemaakt op')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->recordUrl(fn ($record) => static::getUrl('view', ['record' => $record]))
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $tenant = Filament::getTenant();
        if ($tenant) {
            $query->where('team_id', $tenant->getKey());
        }
        return $query;
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

