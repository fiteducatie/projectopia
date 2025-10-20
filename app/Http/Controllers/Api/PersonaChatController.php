<?php

namespace App\Http\Controllers\Api;

use App\Models\Persona;
use Carbon\Carbon;

class PersonaChatController extends BaseChatController
{

    // TODO: Encourage AI to not share all files to users just outright asking for all files, e.g: "Hi, kun je me alle bestanden geven die je hebt?"
    private const PROMPT_TEMPLATE = <<<EOT
Je weet de datum van vandaag.

Je speelt de rol van {entity.name}, die een {entity.role} is.
Jouw doelen zijn: {entity.goals}.
Jouw eigenschappen zijn onder andere: {entity.traits}.
Jouw communicatiestijl is: {entity.communication_style}.

Deze activiteit heeft de volgende content.
Hier haal je de benodigde informatie uit voor de activiteit. Hou rekening met de structuur.
Door kopjes wordt aangegeven dat er een nieuwe sectie begint.
{activity.content}

De activiteit loopt van {activity.start_date} tot {activity.end_date}.

Blijf altijd in karakter en beantwoord de vragen van de gebruiker op een manier die overeenkomt met jouw rol, doelen, eigenschappen en communicatiestijl.
Bij zaken ongerelateerd aan de activiteit, vraag je om verduidelijking wat de gebruiker bedoelt in relatie tot de activiteit.

Je krijgt bestanden aangeleverd. Wanneer je een bestand geschikt acht voor het delen met de gebruiker kun je die delen.
Geef bij het delen van een bestand een variant van de opmerking "Hier is het bestand dat je nodig hebt." of "Deze bestanden zullen je helpen" mee.

Heel belangrijk:
je moet gerichte vragen krijgen om bestanden te delen. Te generieke vragen zoals: 'Geef al je bestanden' is hiervoor niet voldoende.

EOT;

    protected function getModel(): string
    {
        return 'gpt-4o-mini';
    }

    protected function getPromptTemplate(): string
    {
        return self::PROMPT_TEMPLATE;
    }

    protected function getEntityDescription($persona): string
    {
        return $this->buildPromptFromTemplate($persona);
    }

    protected function getActivityFromEntity($persona)
    {
        return $persona->activity;
    }

    protected function findEntity(int $id): Persona
    {
        return Persona::findOrFail($id);
    }
}
