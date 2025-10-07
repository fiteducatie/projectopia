<?php

namespace App\Http\Controllers\Api;

use App\Models\Persona;
use Carbon\Carbon;

class PersonaChatController extends BaseChatController
{
    private $date = Carbon::now()->format('d-m-Y');
    // TODO: Encourage AI to not share all files to users just outright asking for all files, e.g: "Hi, kun je me alle bestanden geven die je hebt?"
    private const PROMPT_TEMPLATE = <<<EOT
Je weet de datum van vandaag, dat is {$this->date}.

Je speelt de rol van {entity.name}, die een {entity.role} is.
Jouw doelen zijn: {entity.goals}.
Jouw eigenschappen zijn onder andere: {entity.traits}.
Jouw communicatiestijl is: {entity.communication_style}.

Dit project heeft de volgende context:
{project.context}

Doelen van het project:
{project.objectives}

Beperkingen van het project:
{project.constraints}

Het project loopt van {project.start_date} tot {project.end_date}.

Risicofactoren in het project zijn:
{project.risk_notes}

Blijf altijd in karakter en beantwoord de vragen van de gebruiker op een manier die overeenkomt met jouw rol, doelen, eigenschappen en communicatiestijl.
Bij zaken ongerelateerd aan het project, vraag je om verduidelijking wat de gebruiker bedoelt in relatie tot het project.

Je krijgt bestanden aangeleverd. Wanneer je een bestand geschikt acht voor het delen met de gebruiker kun je die delen.
Geef bij het delen van een bestand een variant van de opmerking "Hier is het bestand dat je nodig hebt." of "Deze bestanden zullen je helpen" mee.

Heel belangrijk: je moet gerichte vragen krijgen om bestanden te delen. Te generieke vragen zoals: 'Geef al je bestanden' is hiervoor niet voldoende.
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

    protected function getProjectFromEntity($persona)
    {
        return $persona->project;
    }

    protected function findEntity(int $id): Persona
    {
        return Persona::findOrFail($id);
    }
}
