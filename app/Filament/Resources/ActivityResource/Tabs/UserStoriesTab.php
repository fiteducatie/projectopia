<?php

namespace App\Filament\Resources\ActivityResource\Tabs;

use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;

class UserStoriesTab
{
    public static function make(): array
    {
        return [
            Section::make()
                ->description('Maak de scope van de activiteit concreet door user stories aan te leveren.')
                ->schema([
                    TextEntry::make('user_stories_description')
                        ->label('User Stories')
                        ->state('User stories zijn korte, eenvoudige beschrijvingen van een functionaliteit vanuit het perspectief van de eindgebruiker. Ze helpen bij het definiëren van de scope en het begrijpen van de behoeften van de gebruikers.')
                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                    Repeater::make('user_stories_data')
                        ->relationship('userStories')
                        ->itemLabel(function (array $state, $component): string {
                            $key = array_search($state, $component->getState());
                            $index = array_search($key, array_keys($component->getState()));
                            $realIndex = $index + 1;

                            $label = $realIndex . '. ' . $state['user_story'];
                            $label = substr($label, 0, 200);
                            if (isset($state['user_story']) && strlen($state['user_story']) > 200) {
                                $label .= '...';
                            }
                            return trim($label);
                        })
                        ->hiddenLabel()
                        ->schema([
                            TextEntry::make('userstories_help')
                                ->label('User stories')
                                ->state('Bijv. Als [rol] wil ik [doel] zodat [reden].')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Forms\Components\TextInput::make('user_story')
                                ->hiddenLabel()
                                ->required(),
                            Section::make()
                                ->schema([
                                    TextEntry::make('acceptance_criteria_help')
                                        ->state('Beschrijf welke aanvullende details de user story moet hebben.')
                                        ->label('Acceptatie Criteria')
                                        ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                                    Repeater::make('acceptance_criteria')
                                        ->collapsible()
                                        ->hiddenLabel()
                                        ->simple(
                                            Forms\Components\TextInput::make('criteria')
                                        )
                                ]),
                            TextEntry::make('personas_help')
                                ->label('Persona\'s')
                                ->state('Selecteer relevante persona\'s voor deze user story.')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400']),
                            Forms\Components\Select::make('personas')
                                ->hiddenLabel()
                                ->multiple()
                                ->options(function($record){
                                    if(!$record){
                                        return [];
                                    }
                                    return $record->activity->personas()->pluck('name', 'id');
                                })
                                ->visibleOn('edit'),
                            Forms\Components\Toggle::make('mvp')->label('MVP?'),
                            TextEntry::make('priority_help')
                                ->label('Prioriteit')
                                ->extraAttributes(['class' => 'italic text-sm text-gray-400'])
                                ->state('Helpt bij het plannen en prioriteren van werk.'),
                            Forms\Components\Select::make('priority')->label('Prioriteit')
                                ->hiddenLabel()
                                ->options([
                                    'low' => 'Laag',
                                    'medium' => 'Middel',
                                    'high' => 'Hoog',
                                ])->required()->default('medium')
                        ])
                        ->collapsed()
                        ->minItems(1)
                        ->grid(1),
                ]),
        ];
    }
}

