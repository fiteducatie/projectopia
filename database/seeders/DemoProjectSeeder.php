<?php

namespace Database\Seeders;

use App\Models\Persona;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Team;
use App\Models\User;
use App\Models\UserStory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoProjectSeeder extends Seeder
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

        $project = Project::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Demo project',
        ], [
            'domain' => 'software',
            'context' => 'Bouw een webapp voor kaartverkoop van een nieuwe achtbaan.',
            'objectives' => 'Online ticketing, rit-informatie, boekingssysteem.',
            'constraints' => 'Budget en tijdslimiet van 8 weken.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(8),
            'risk_notes' => 'Scope creep, prestatieproblemen, compliance.',
            'difficulty' => 'normal',
        ]);

        $maria = Persona::query()->updateOrCreate([
            'project_id' => $project->id,
            'name' => 'Maria Jensen',
        ], [
            'role' => 'Manager Pretpark',
            'avatar_url' => 'https://i.pravatar.cc/150?img=49',
            'goals' => 'Hogere omzet en tevreden bezoekers.',
            'traits' => 'Resultaatgericht, praktisch.',
            'communication_style' => 'Direct, to-the-point.',
        ]);

        $tom = Persona::query()->updateOrCreate([
            'project_id' => $project->id,
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

        $mariaAttachment1 = $project->addMedia($mariaFile1)
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

        $mariaAttachment2 = $project->addMedia($mariaFile2)
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

        $tomAttachment = $project->addMedia($tomFile)
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
            'project_id' => $project->id,
            'user_story' => 'Als bezoeker wil ik tickets online kunnen kopen zodat ik snel toegang krijg tot het pretpark.',
            'acceptance_criteria' => json_encode(['De bezoeker kan tickets selecteren', 'De bezoeker kan betalen met iDeal of creditcard', 'De bezoeker ontvangt een e-ticket per email']),
            'personas' => json_encode([2]),
            'priority' => 'high',
        ]);


        UserStory::query()->updateOrCreate([
            'project_id' => $project->id,
            'user_story' => 'Als bezoeker wil ik een soepele checkout zodat ik zonder problemen mijn tickets kan kopen.',
            'acceptance_criteria' => json_encode(['De checkout valideert invoer correct', 'De gebruiker ontvangt een bevestiging na betaling']),
            'personas' => json_encode([1, 2]),
            'priority' => 'low',
        ]);
    }
}

