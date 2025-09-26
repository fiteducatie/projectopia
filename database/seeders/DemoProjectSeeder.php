<?php

namespace Database\Seeders;

use App\Models\BacklogItem;
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

        Persona::query()->updateOrCreate([
            'project_id' => $project->id,
            'name' => 'Maria Jensen',
        ], [
            'role' => 'Manager Pretpark',
            'avatar_url' => 'https://i.pravatar.cc/150?img=49',
            'goals' => 'Hogere omzet en tevreden bezoekers.',
            'traits' => 'Resultaatgericht, praktisch.',
            'communication_style' => 'Direct, to-the-point.',
        ]);

        Persona::query()->updateOrCreate([
            'project_id' => $project->id,
            'name' => 'Tom Bakker',
        ], [
            'role' => 'Product Owner/Docent',
            'avatar_url' => null,
            'goals' => 'Leren prioriteren en plannen.',
            'traits' => 'Coachend, analytisch.',
            'communication_style' => 'Coachend, vragend.',
        ]);

        $sprint1 = Sprint::query()->updateOrCreate([
            'project_id' => $project->id,
            'number' => 1,
        ], [
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addWeeks(2),
            'capacity' => 20,
        ]);

        $sprint2 = Sprint::query()->updateOrCreate([
            'project_id' => $project->id,
            'number' => 2,
        ], [
            'start_date' => now()->startOfMonth()->addWeeks(2),
            'end_date' => now()->startOfMonth()->addWeeks(4),
            'capacity' => 20,
        ]);

        BacklogItem::query()->updateOrCreate([
            'project_id' => $project->id,
            'title' => 'Epic: Ticketing',
        ], [
            'epic' => 'Ticketing',
            'description' => 'Als bezoeker wil ik tickets online kunnen kopen.',
            'acceptance_criteria' => 'Betaal met iDeal/creditcard, ontvang e-ticket.',
            'priority' => 1,
            'effort' => 8,
            'status' => 'todo',
        ]);

        BacklogItem::query()->updateOrCreate([
            'project_id' => $project->id,
            'title' => 'User story: Checkout flow',
        ], [
            'epic' => 'Ticketing',
            'description' => 'Als bezoeker wil ik een soepele checkout.',
            'acceptance_criteria' => 'Validatie, foutmeldingen, bevestiging.',
            'priority' => 1,
            'effort' => 5,
            'sprint_id' => $sprint1->id,
            'status' => 'todo',
        ]);

        BacklogItem::query()->updateOrCreate([
            'project_id' => $project->id,
            'title' => 'User story: Rit-informatie pagina',
        ], [
            'epic' => 'Informatie',
            'description' => 'Als bezoeker wil ik ritdetails en wachttijd zien.',
            'acceptance_criteria' => 'Naam, lengte-eisen, wachttijd.',
            'priority' => 2,
            'effort' => 3,
            'sprint_id' => $sprint2->id,
            'status' => 'todo',
        ]);
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

