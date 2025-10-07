<?php

namespace App\Http\Controllers\Api;

use App\Models\Teamleader;
use App\Services\ScheduleService;
use Carbon\Carbon;

class TeamleaderChatController extends BaseChatController
{
    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }
    private const PROMPT_TEMPLATE = <<<EOT
Je speelt de rol van {entity.name}, die een Team Leider is.
Jouw samenvatting is: {entity.summary}.
Jouw beschrijving is: {entity.description}.
Jouw communicatiestijl is: {entity.communication_style}.
Jouw vaardigheden zijn: {entity.skillset}.
Jouw verwachte deliverables zijn: {entity.deliverables}.

HUIDIGE TIJD EN DATUM:
De huidige datum en tijd is: {current_datetime}
Het is vandaag {current_date} en het is nu {current_time}.

Dit project heeft de volgende context:
{project.context}

Doelen van het project:
{project.objectives}

Beperkingen van het project:
{project.constraints}

Het project loopt van {project.start_date} tot {project.end_date}.

Risicofactoren in het project zijn:
{project.risk_notes}

BELANGRIJKE ROLGIDS:
Als Team Leider ben je verantwoordelijk voor het leiden van het team en het begeleiden van projectactiviteiten.
Je bent ervaren in projectmanagement en teamleiderschap. Jouw taak is om het team zo goed mogelijk mee te nemen in het scrum proces.

SPECIALE INSTRUCTIES VOOR USER STORIES:
- Je hebt toegang tot alle user stories van het project (zie hieronder), maar je beantwoordt GEEN specifieke vragen over user stories
- Wanneer iemand vraagt over user stories, verwijs je hen door naar de juiste persona's
- Je kunt wel algemene informatie geven over het aantal user stories of de voortgang
- Je kunt uitleggen welke persona's betrokken zijn bij specifieke user stories
- Gebruik de informatie hieronder over user stories en beschikbare persona's om de juiste doorverwijzing te maken

Wanneer iemand vraagt over user stories, zeg je iets zoals:
"Voor specifieke vragen over user stories, kun je het beste contact opnemen met [naam van de relevante persona]. Zij hebben de diepgaande kennis over deze user story en kunnen je gedetailleerd helpen."

VOORBEELDEN VAN DOORVERWIJZINGEN:
- Voor vragen over functionaliteit: verwijs naar de relevante persona die bij die user story betrokken is
- Voor vragen over acceptatiecriteria: verwijs naar de persona die de user story heeft gedefinieerd

Heel belangrijk: Geef nooit direct antwoord op inhoudelijke vragen over de user stories of over welke user stories er beschikbaar zijn,
jij weet hier zelf niks over maar verwijs naar de relevante persona.
Blijf altijd in karakter en beantwoord de vragen van de gebruiker op een manier die overeenkomt met jouw rol als Team Leider.
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

    /**
     * Get the chat messages with schedule messages if active
     */
    public function getMessages(int $teamleaderId): array
    {
        $teamleader = $this->findEntity($teamleaderId);
        $messages = []; // Start with empty messages for now

        // Check if there are active schedule messages
        $scheduleMessages = $this->scheduleService->getCurrentScheduleMessages($teamleader);

        if (!empty($scheduleMessages)) {
            // Add each schedule message at the beginning
            foreach ($scheduleMessages as $scheduleMessageData) {
                array_unshift($messages, [
                    'id' => 'schedule_' . $scheduleMessageData['schedule_item_id'],
                    'message' => $scheduleMessageData['message'],
                    'sender' => 'teamleader',
                    'timestamp' => $scheduleMessageData['timestamp'],
                    'is_schedule_message' => true,
                    'is_persistent' => true, // This message stays during the schedule period
                ]);
            }
        }

        return $messages;
    }

    /**
     * Check if there's a pending schedule message for notifications
     */
    public function hasPendingScheduleMessage(int $teamleaderId): array
    {
        $teamleader = $this->findEntity($teamleaderId);
        $project = $teamleader->projects()->first();

        if (!$project) {
            return [
                'success' => true,
                'has_pending' => false,
            ];
        }

        $hasActiveSchedule = $this->scheduleService->shouldShowScheduleMessage($project);
        $scheduleData = null;

        if ($hasActiveSchedule) {
            // Get all current active schedule items
            $activeItems = $this->scheduleService->getActiveScheduleItems($project);
            if ($activeItems->isNotEmpty()) {
                // For localStorage key, we'll use the earliest time_from
                $earliestTimeFrom = $activeItems->map(function ($item) {
                    return $item['time_from'];
                })->min();

                $scheduleData = [
                    'time_from' => $earliestTimeFrom,
                    'active_count' => $activeItems->count(),
                    'items' => $activeItems->map(function ($item) {
                        return [
                            'time_from' => $item['time_from'],
                            'time_until' => $item['time_until'],
                            'title' => $item['title'],
                            'description' => $item['description'],
                        ];
                    })->values()->toArray(),
                ];
            }
        }

        return [
            'success' => true,
            'has_pending' => $hasActiveSchedule,
            'schedule_data' => $scheduleData,
        ];
    }

    /**
     * Mark schedule messages as read
     */
    public function markScheduleAsRead(int $teamleaderId): array
    {
        $teamleader = $this->findEntity($teamleaderId);

        // For now, we'll just return success
        // In a real implementation, you might want to track read status in the database
        // or use session/cache to track which messages have been seen

        return [
            'success' => true,
            'message' => 'Schedule messages marked as read',
        ];
    }
}
