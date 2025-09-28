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

        // Project 'Restaurant App'

        $restaurantApp = Project::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Restaurant App',
        ], [
            'domain' => 'software',
            'context' => 'Bouw een mobiele app voor het reserveren en bestellen in een restaurant.',
            'objectives' => 'Tafelreserveringen, menu bekijken, online bestellen.',
            'constraints' => 'Budget en tijdslimiet van 6 weken.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(6),
            'risk_notes' => 'Technische uitdagingen, gebruikersacceptatie.',
            'difficulty' => 'hard',
        ]);

        $quinten = Persona::query()->updateOrCreate([
            'project_id' => $restaurantApp->id,
            'name' => 'Quinten de Vries',
        ], [
            'role' => 'Restaurant Eigenaar',
            'avatar_url' => 'https://i.pravatar.cc/150?img=3',
            'goals' => 'Meer reserveringen en tevreden klanten.',
            'traits' => 'Gastvrij, ondernemend.',
            'communication_style' => 'Informeel, vriendelijk.',
        ]);
        $sanne = Persona::query()->updateOrCreate([
            'project_id' => $restaurantApp->id,
            'name' => 'Sanne Jansen',
        ], [
            'role' => 'Kok/Docent',
            'avatar_url' => 'https://i.pravatar.cc/150?img=4',
            'goals' => 'Leren werken met klantgerichte technologie.',
            'traits' => 'Creatief, leergierig.',
            'communication_style' => 'Open, nieuwsgierig.',
        ]);
        UserStory::query()->updateOrCreate([
            'project_id' => $restaurantApp->id,
            'user_story' => 'Als klant wil ik een tafel kunnen reserveren zodat ik verzekerd ben van een plek in het restaurant.',
            'acceptance_criteria' => json_encode(['De klant kan datum en tijd selecteren', 'De klant ontvangt een bevestiging per email']),
            'personas' => json_encode([$quinten->id]),
            'priority' => 'high',
        ]);
        UserStory::query()->updateOrCreate([
            'project_id' => $restaurantApp->id,
            'user_story' => 'Als klant wil ik het menu kunnen bekijken zodat ik een weloverwogen keuze kan maken.',
            'acceptance_criteria' => json_encode(['Het menu is overzichtelijk ingedeeld', 'De klant kan gerechten filteren op dieetwensen']),
            'personas' => json_encode([$quinten->id, $sanne->id]),
            'priority' => 'low',
        ]);

        // Project 'Fitness Tracker App'
        $fitnessTracker = Project::query()->firstOrCreate([
            'team_id' => $team->id,
            'name' => 'Fitness Tracker App',
        ], [
            'domain' => 'software',
            'context' => 'Bouw een mobiele app voor het bijhouden van fitnessactiviteiten en gezondheid.',
            'objectives' => 'Activiteiten loggen, doelen stellen, voortgang volgen.',
            'constraints' => 'Budget en tijdslimiet van 10 weken.',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(10),
            'risk_notes' => 'Privacy zorgen, technische integraties.',
            'difficulty' => 'hard',
        ]);
        $emma = Persona::query()->updateOrCreate([
            'project_id' => $fitnessTracker->id,
            'name' => 'Emma de Jong',
        ], [
            'role' => 'Fitness Enthousiast',
            'avatar_url' => 'https://i.pravatar.cc/150?img=5',
            'goals' => 'Gezonder leven en fitter worden.',
            'traits' => 'Gedreven, doelgericht.',
            'communication_style' => 'Motiverend, positief.',
        ]);
        $lucas = Persona::query()->updateOrCreate([
            'project_id' => $fitnessTracker->id,
            'name' => 'Lucas van den Berg',
        ], [
            'role' => 'Personal Trainer/Docent',
            'avatar_url' => 'https://i.pravatar.cc/150?img=6',
            'goals' => 'Leren hoe technologie fitness kan verbeteren.',
            'traits' => 'Coachend, analytisch.',
            'communication_style' => 'Coachend, vragend.',
        ]);
        UserStory::query()->updateOrCreate([
            'project_id' => $fitnessTracker->id,
            'user_story' => 'Als gebruiker wil ik mijn dagelijkse activiteiten kunnen loggen zodat ik mijn voortgang kan bijhouden.',
            'acceptance_criteria' => json_encode(['De gebruiker kan verschillende activiteiten selecteren', 'De gebruiker kan notities toevoegen bij elke activiteit']),
            'personas' => json_encode([$emma->id]),
            'priority' => 'high',
        ]);
        UserStory::query()->updateOrCreate([
            'project_id' => $fitnessTracker->id,
            'user_story' => 'Als gebruiker wil ik doelen kunnen stellen zodat ik gemotiveerd blijf om te sporten.',
            'acceptance_criteria' => json_encode(['De gebruiker kan wekelijkse en maandelijkse doelen instellen', 'De app geeft herinneringen om doelen te behalen']),
            'personas' => json_encode([$emma->id, $lucas->id]),
            'priority' => 'low',
        ]);


    }
}

