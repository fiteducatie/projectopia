<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Activity;
use App\Models\Sprint;
use App\Models\Team;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => 'password',
        ]);

        $team = Team::query()->firstOrCreate([
            'name' => 'CURIO TTSD',
        ], [
            'owner_id' => $user->id,
        ]);

        $activity = Activity::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Demo activity',
        ], [
            'domain' => 'software',
            'content' => '{"type": "doc", "content": [{"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Context", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijf de achtergrond van de workshop:", "type": "text", "marks": [{"type": "italic"}]}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Voor wie is deze bedoeld (opleiding, niveau, voorkennis)?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Waarom is dit relevant?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Hoe past het in het curriculum of leerpad?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat is de rol van de AI-begeleiding (bijv. vervangt deels de docent, biedt directe feedback, stimuleert reflectie)?", "type": "text", "marks": [{"type": "italic"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Leerdoelen", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat moeten deelnemers ", "type": "text"}, {"text": "kunnen, weten of ervaren", "type": "text", "marks": [{"type": "bold"}]}, {"text": " aan het einde van de workshop?", "type": "text"}, {"type": "hardBreak"}, {"text": "Formuleer dit meetbaar of concreet.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 1", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 2", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 3", "type": "text"}]}]}]}, {"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "(Optioneel: koppel deze aan beroepscompetenties of opleidingsdoelen.)", "type": "text", "marks": [{"type": "italic"}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Opbouw", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "table", "content": [{"type": "tableRow", "content": [{"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Fase", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijving", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Interactie met AI", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Output", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "1", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Introductie & activering voorkennis", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-coach stelt vragen, legt context uit", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie-antwoord", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "2", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Theoriedeel / korte uitleg", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-expert beantwoordt inhoudelijke vragen", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Korte quiz of samenvatting", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "3", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Praktijkopdracht", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-assistent geeft feedback op voortgang", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Concept-product", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "4", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie & afronding", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-reflectiecoach bespreekt leerervaring", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eindreflectie of upload", "type": "text"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Benodigdheden/techniek", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"type": "hardBreak"}, {"text": "Platform of leeromgeving (bijv. Xerte, Moodle, Curio AI-portal, etc.)", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Toegang tot AI-agents (API of ingebouwde chatfunctie)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eventuele extra tools (editor, browser, simulatieomgeving)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Geschatte tijdsduur: … minuten / uren", "type": "text"}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Output/resultaat", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat levert de student concreet op aan het einde?", "type": "text"}, {"type": "hardBreak"}, {"text": "Bijv.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een mini-website", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een plan van aanpak", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een reflectieverslag", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een demo-video", "type": "text"}]}]}]}]}]}]}',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(8),
            'difficulty' => 'normal',
            'info_popup' => '   <h3>📋 Instructie: Gebruik van de Projectpagina</h3>
                                <p>De projectpagina is ontwikkeld om je snel inzicht te geven in alle belangrijke onderdelen van een project. Zodra je de pagina opent, zie je een overzicht met de belangrijkste informatie en functies.</p>
                                <br>
                                <h3>📚 Projectcontext (links):</h3>
                                <p>Aan de linkerkant vind je de context, doelstellingen en randvoorwaarden van het project. Zo krijg je direct duidelijkheid over wat het project beoogt en binnen welke kaders er gewerkt wordt.</p>
                                <br>
                                <h3>👥 Teamleiders (rechts):</h3>
                                <p>Aan de rechterzijde staan de teamleiders vermeld. Zij geven aan hoe het project verloopt en wanneer specifieke onderdelen moeten worden opgeleverd — bijvoorbeeld op een bepaalde dag of binnen een week.</p>
                                <br>
                                <h3>💡 Betrokkenen (rechts onderin):</h3>
                                <p>Rechts onderin vind je de betrokkenen. Zij zijn beschikbaar om extra informatie uit het project te halen. Door de juiste vragen te stellen, krijg je inzicht in belangrijke informatie, user stories, acceptatiecriteria en prioriteiten.</p>'
                            ]);

        $maria = Persona::query()->updateOrCreate([
            'activity_id' => $activity->id,
            'name' => 'Maria Jensen',
        ], [
            'role' => 'Manager Pretpark',
            'avatar_url' => 'https://i.pravatar.cc/150?img=49',
            'goals' => 'Hogere omzet en tevreden bezoekers.',
            'traits' => 'Resultaatgericht, praktisch.',
            'communication_style' => 'Direct, to-the-point.',
        ]);

        $tom = Persona::query()->updateOrCreate([
            'activity_id' => $activity->id,
            'name' => 'Tom Bakker',
        ], [
            'role' => 'Product Owner/Docent',
            'avatar_url' => null,
            'goals' => 'Leren prioriteren en plannen.',
            'traits' => 'Coachend, analytisch.',
            'communication_style' => 'Coachend, vragend.',
        ]);

        // Create temporary files for attachments
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Add attachments for Maria (2 attachments)
        $mariaFile1 = $tempDir . '/bezoekersrapport-q3-2024.txt';
        file_put_contents($mariaFile1, "BEZOEKERSRAPPORT Q3 2024\n\nDit rapport bevat gedetailleerde analyses van bezoekersaantallen en trends in het derde kwartaal van 2024.\n\nHoofdpunten:\n- Piekuren analyse\n- Populaire attracties\n- Bezoekerstevredenheid\n- Omzetcijfers\n\nAanbevelingen voor Q4 zijn opgenomen in bijlage A.");

        $mariaAttachment1 = $activity->addMedia($mariaFile1)
            ->usingName('Bezoekersrapport Q3 2024')
            ->toMediaCollection('attachments');

        $mariaAttachment1->custom_properties = [
            'name' => 'Bezoekersrapport Q3 2024',
            'description' => 'Gedetailleerd rapport over bezoekersaantallen en trends in het derde kwartaal van 2024. Bevat analyses van piekuren, populaire attracties en bezoekerstevredenheid.',
            'persona_ids' => [$maria->id]
        ];
        $mariaAttachment1->save();

        $mariaFile2 = $tempDir . '/marketingstrategie-2024.txt';
        file_put_contents($mariaFile2, "MARKETINGSTRATEGIE 2024\n\nUitgebreide marketingstrategie voor het pretpark in 2024.\n\nStrategische pijlers:\n- Digitale marketing campagnes\n- Doelgroep segmentatie\n- Budgetverdeling per kwartaal\n- KPI's en meetbare resultaten\n\nVerwachte impact op ticketverkoop: +25% ten opzichte van 2023.");

        $mariaAttachment2 = $activity->addMedia($mariaFile2)
            ->usingName('Marketingstrategie 2024')
            ->toMediaCollection('attachments');

        $mariaAttachment2->custom_properties = [
            'name' => 'Marketingstrategie 2024',
            'description' => 'Uitgebreide marketingstrategie voor het pretpark in 2024. Inclusief campagnes, doelgroepen, budgetverdeling en verwachte resultaten voor ticketverkoop.',
            'persona_ids' => [$maria->id]
        ];
        $mariaAttachment2->save();

        // Add attachment for Tom (1 attachment)
        $tomFile = $tempDir . '/project-management-gids.txt';
        file_put_contents($tomFile, "PROJECT MANAGEMENT GIDS\n\nPraktische gids voor projectmanagement in educatieve context.\n\nHoofdstukken:\n1. Agile methodieken\n2. Scrum framework\n3. Studenten begeleiding\n4. Tools en technieken\n5. Best practices\n6. Evaluatie en reflectie\n\nDeze gids helpt docenten bij het begeleiden van studentenprojecten.");

        $tomAttachment = $activity->addMedia($tomFile)
            ->usingName('Project Management Gids')
            ->toMediaCollection('attachments');

        $tomAttachment->custom_properties = [
            'name' => 'Project Management Gids',
            'description' => 'Praktische gids voor projectmanagement in educatieve context. Bevat methodieken, tools en best practices voor het begeleiden van studentenprojecten.',
            'persona_ids' => [$tom->id]
        ];
        $tomAttachment->save();

        // Clean up temporary files (they may have been moved by media library)
        if (file_exists($mariaFile1)) unlink($mariaFile1);
        if (file_exists($mariaFile2)) unlink($mariaFile2);
        if (file_exists($tomFile)) unlink($tomFile);
        if (is_dir($tempDir) && count(scandir($tempDir)) <= 2) rmdir($tempDir);

       UserStory::query()->updateOrCreate([
            'activity_id' => $activity->id,
            'user_story' => 'Als bezoeker wil ik tickets online kunnen kopen zodat ik snel toegang krijg tot het pretpark.',
            'acceptance_criteria' => json_encode(['De bezoeker kan tickets selecteren', 'De bezoeker kan betalen met iDeal of creditcard', 'De bezoeker ontvangt een e-ticket per email']),
            'personas' => json_encode([2]),
            'priority' => 'high',
        ]);


        UserStory::query()->updateOrCreate([
            'activity_id' => $activity->id,
            'user_story' => 'Als bezoeker wil ik een soepele checkout zodat ik zonder problemen mijn tickets kan kopen.',
            'acceptance_criteria' => json_encode(['De checkout valideert invoer correct', 'De gebruiker ontvangt een bevestiging na betaling']),
            'personas' => json_encode([1, 2]),
            'priority' => 'low',
        ]);

        // Activity 'Restaurant App'

        $restaurantApp = Activity::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Restaurant App',
        ], [
            'domain' => 'software',
            'content' => '{"type": "doc", "content": [{"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Context", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijf de achtergrond van de workshop:", "type": "text", "marks": [{"type": "italic"}]}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Voor wie is deze bedoeld (opleiding, niveau, voorkennis)?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Waarom is dit relevant?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Hoe past het in het curriculum of leerpad?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat is de rol van de AI-begeleiding (bijv. vervangt deels de docent, biedt directe feedback, stimuleert reflectie)?", "type": "text", "marks": [{"type": "italic"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Leerdoelen", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat moeten deelnemers ", "type": "text"}, {"text": "kunnen, weten of ervaren", "type": "text", "marks": [{"type": "bold"}]}, {"text": " aan het einde van de workshop?", "type": "text"}, {"type": "hardBreak"}, {"text": "Formuleer dit meetbaar of concreet.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 1", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 2", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 3", "type": "text"}]}]}]}, {"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "(Optioneel: koppel deze aan beroepscompetenties of opleidingsdoelen.)", "type": "text", "marks": [{"type": "italic"}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Opbouw", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "table", "content": [{"type": "tableRow", "content": [{"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Fase", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijving", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Interactie met AI", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Output", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "1", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Introductie & activering voorkennis", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-coach stelt vragen, legt context uit", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie-antwoord", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "2", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Theoriedeel / korte uitleg", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-expert beantwoordt inhoudelijke vragen", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Korte quiz of samenvatting", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "3", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Praktijkopdracht", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-assistent geeft feedback op voortgang", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Concept-product", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "4", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie & afronding", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-reflectiecoach bespreekt leerervaring", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eindreflectie of upload", "type": "text"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Benodigdheden/techniek", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"type": "hardBreak"}, {"text": "Platform of leeromgeving (bijv. Xerte, Moodle, Curio AI-portal, etc.)", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Toegang tot AI-agents (API of ingebouwde chatfunctie)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eventuele extra tools (editor, browser, simulatieomgeving)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Geschatte tijdsduur: … minuten / uren", "type": "text"}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Output/resultaat", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat levert de student concreet op aan het einde?", "type": "text"}, {"type": "hardBreak"}, {"text": "Bijv.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een mini-website", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een plan van aanpak", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een reflectieverslag", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een demo-video", "type": "text"}]}]}]}]}]}]}',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(6),
            'difficulty' => 'hard',
        ]);

        $quinten = Persona::query()->updateOrCreate([
            'activity_id' => $restaurantApp->id,
            'name' => 'Quinten de Vries',
        ], [
            'role' => 'Restaurant Eigenaar',
            'avatar_url' => 'https://i.pravatar.cc/150?img=3',
            'goals' => 'Meer reserveringen en tevreden klanten.',
            'traits' => 'Gastvrij, ondernemend.',
            'communication_style' => 'Informeel, vriendelijk.',
        ]);
        $sanne = Persona::query()->updateOrCreate([
            'activity_id' => $restaurantApp->id,
            'name' => 'Sanne Jansen',
        ], [
            'role' => 'Kok/Docent',
            'avatar_url' => 'https://i.pravatar.cc/150?img=4',
            'goals' => 'Leren werken met klantgerichte technologie.',
            'traits' => 'Creatief, leergierig.',
            'communication_style' => 'Open, nieuwsgierig.',
        ]);
        UserStory::query()->updateOrCreate([
            'activity_id' => $restaurantApp->id,
            'user_story' => 'Als klant wil ik een tafel kunnen reserveren zodat ik verzekerd ben van een plek in het restaurant.',
            'acceptance_criteria' => json_encode(['De klant kan datum en tijd selecteren', 'De klant ontvangt een bevestiging per email']),
            'personas' => json_encode([$quinten->id]),
            'priority' => 'high',
        ]);
        UserStory::query()->updateOrCreate([
            'activity_id' => $restaurantApp->id,
            'user_story' => 'Als klant wil ik het menu kunnen bekijken zodat ik een weloverwogen keuze kan maken.',
            'acceptance_criteria' => json_encode(['Het menu is overzichtelijk ingedeeld', 'De klant kan gerechten filteren op dieetwensen']),
            'personas' => json_encode([$quinten->id, $sanne->id]),
            'priority' => 'low',
        ]);

        // Activity 'Fitness Tracker App'
        $fitnessTracker = Activity::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Fitness Tracker App',
        ], [
            'domain' => 'software',
            'content' => '{"type": "doc", "content": [{"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Context", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijf de achtergrond van de workshop:", "type": "text", "marks": [{"type": "italic"}]}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Voor wie is deze bedoeld (opleiding, niveau, voorkennis)?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Waarom is dit relevant?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Hoe past het in het curriculum of leerpad?", "type": "text", "marks": [{"type": "italic"}]}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat is de rol van de AI-begeleiding (bijv. vervangt deels de docent, biedt directe feedback, stimuleert reflectie)?", "type": "text", "marks": [{"type": "italic"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Leerdoelen", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat moeten deelnemers ", "type": "text"}, {"text": "kunnen, weten of ervaren", "type": "text", "marks": [{"type": "bold"}]}, {"text": " aan het einde van de workshop?", "type": "text"}, {"type": "hardBreak"}, {"text": "Formuleer dit meetbaar of concreet.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 1", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 2", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Leerdoel 3", "type": "text"}]}]}]}, {"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "(Optioneel: koppel deze aan beroepscompetenties of opleidingsdoelen.)", "type": "text", "marks": [{"type": "italic"}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Opbouw", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "table", "content": [{"type": "tableRow", "content": [{"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Fase", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Beschrijving", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Interactie met AI", "type": "text"}]}]}, {"type": "tableHeader", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Output", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "1", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Introductie & activering voorkennis", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-coach stelt vragen, legt context uit", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie-antwoord", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "2", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Theoriedeel / korte uitleg", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-expert beantwoordt inhoudelijke vragen", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Korte quiz of samenvatting", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "3", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Praktijkopdracht", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-assistent geeft feedback op voortgang", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Concept-product", "type": "text"}]}]}]}, {"type": "tableRow", "content": [{"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "4", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [296]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Reflectie & afronding", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": null}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "AI-reflectiecoach bespreekt leerervaring", "type": "text"}]}]}, {"type": "tableCell", "attrs": {"colspan": 1, "rowspan": 1, "colwidth": [240]}, "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eindreflectie of upload", "type": "text"}]}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Benodigdheden/techniek", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"type": "hardBreak"}, {"text": "Platform of leeromgeving (bijv. Xerte, Moodle, Curio AI-portal, etc.)", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Toegang tot AI-agents (API of ingebouwde chatfunctie)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Eventuele extra tools (editor, browser, simulatieomgeving)", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Geschatte tijdsduur: … minuten / uren", "type": "text"}]}]}]}]}]}, {"type": "details", "content": [{"type": "detailsSummary", "content": [{"text": "Output/resultaat", "type": "text"}]}, {"type": "detailsContent", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Wat levert de student concreet op aan het einde?", "type": "text"}, {"type": "hardBreak"}, {"text": "Bijv.", "type": "text"}]}, {"type": "bulletList", "content": [{"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een mini-website", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een plan van aanpak", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een reflectieverslag", "type": "text"}]}]}, {"type": "listItem", "content": [{"type": "paragraph", "attrs": {"textAlign": "start"}, "content": [{"text": "Een demo-video", "type": "text"}]}]}]}]}]}]}',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(10),
            'difficulty' => 'hard',
            'info_popup' => '   <h3>📋 Instructie: Gebruik van de Projectpagina</h3>
                                <p>De projectpagina is ontwikkeld om je snel inzicht te geven in alle belangrijke onderdelen van een project. Zodra je de pagina opent, zie je een overzicht met de belangrijkste informatie en functies.</p>
                                <br>
                                <h3>📚 Projectcontext (links):</h3>
                                <p>Aan de linkerkant vind je de context, doelstellingen en randvoorwaarden van het project. Zo krijg je direct duidelijkheid over wat het project beoogt en binnen welke kaders er gewerkt wordt.</p>
                                <br>
                                <h3>👥 Teamleiders (rechts):</h3>
                                <p>Aan de rechterzijde staan de teamleiders vermeld. Zij geven aan hoe het project verloopt en wanneer specifieke onderdelen moeten worden opgeleverd — bijvoorbeeld op een bepaalde dag of binnen een week.</p>
                                <br>
                                <h3>💡 Betrokkenen (rechts onderin):</h3>
                                <p>Rechts onderin vind je de betrokkenen. Zij zijn beschikbaar om extra informatie uit het project te halen. Door de juiste vragen te stellen, krijg je inzicht in belangrijke informatie, user stories, acceptatiecriteria en prioriteiten.</p>'
        ]);
        $emma = Persona::query()->updateOrCreate([
            'activity_id' => $fitnessTracker->id,
            'name' => 'Emma de Jong',
        ], [
            'role' => 'Fitness Enthousiast',
            'avatar_url' => 'https://i.pravatar.cc/150?img=5',
            'goals' => 'Gezonder leven en fitter worden.',
            'traits' => 'Gedreven, doelgericht.',
            'communication_style' => 'Motiverend, positief.',
        ]);
        $lucas = Persona::query()->updateOrCreate([
            'activity_id' => $fitnessTracker->id,
            'name' => 'Lucas van den Berg',
        ], [
            'role' => 'Personal Trainer/Docent',
            'avatar_url' => 'https://i.pravatar.cc/150?img=6',
            'goals' => 'Leren hoe technologie fitness kan verbeteren.',
            'traits' => 'Coachend, analytisch.',
            'communication_style' => 'Coachend, vragend.',
        ]);
        UserStory::query()->updateOrCreate([
            'activity_id' => $fitnessTracker->id,
            'user_story' => 'Als gebruiker wil ik mijn dagelijkse activiteiten kunnen loggen zodat ik mijn voortgang kan bijhouden.',
            'acceptance_criteria' => json_encode(['De gebruiker kan verschillende activiteiten selecteren', 'De gebruiker kan notities toevoegen bij elke activiteit']),
            'personas' => json_encode([$emma->id]),
            'priority' => 'high',
        ]);
        UserStory::query()->updateOrCreate([
            'activity_id' => $fitnessTracker->id,
            'user_story' => 'Als gebruiker wil ik doelen kunnen stellen zodat ik gemotiveerd blijf om te sporten.',
            'acceptance_criteria' => json_encode(['De gebruiker kan wekelijkse en maandelijkse doelen instellen', 'De app geeft herinneringen om doelen te behalen']),
            'personas' => json_encode([$emma->id, $lucas->id]),
            'priority' => 'low',
        ]);


    }
}

