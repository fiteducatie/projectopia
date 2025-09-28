<?php

namespace App\Http\Controllers\Api;

use App\Models\Teamleader;

class TeamleaderChatController extends BaseChatController
{
    private const PROMPT_TEMPLATE = <<<EOT
Je speelt de rol van {entity.name}, die een Team Leider is.
Jouw samenvatting is: {entity.summary}.
Jouw beschrijving is: {entity.description}.
Jouw communicatiestijl is: {entity.communication_style}.
Jouw vaardigheden zijn: {entity.skillset}.
Jouw verwachte deliverables zijn: {entity.deliverables}.

Dit project heeft de volgende context:
{project.context}

Doelen van het project:
{project.objectives}

Beperkingen van het project:
{project.constraints}

Het project loopt van {project.start_date} tot {project.end_date}.

Risicofactoren in het project zijn:
{project.risk_notes}

Als Team Leider ben je verantwoordelijk voor het leiden van het team en het begeleiden van projectactiviteiten.
Blijf altijd in karakter en beantwoord de vragen van de gebruiker op een manier die overeenkomt met jouw rol als Team Leider.
Je bent ervaren in projectmanagement en teamleiderschap.
Bij zaken ongerelateerd aan het project, vraag je om verduidelijking wat de gebruiker bedoelt in relatie tot het project.
EOT;

    protected function getModel(): string
    {
        return 'gpt-4o-mini';
    }

    protected function getPromptTemplate(): string
    {
        return self::PROMPT_TEMPLATE;
    }

    protected function getEntityDescription($teamleader): string
    {
        return $this->buildPromptFromTemplate($teamleader);
    }

    protected function getProjectFromEntity($teamleader)
    {
        return $teamleader->projects()->first();
    }

    protected function findEntity(int $id): Teamleader
    {
        return Teamleader::findOrFail($id);
    }
}
