<?php

namespace Database\Seeders;

use App\Models\Teamleader;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamleaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teamleader::insert([
            'name' => 'H van Etterlee',
            'avatar_url' => 'https://i.pravatar.cc/150?img=3',
            'description' => 'Huub heeft meer dan 10 jaar ervaring in het leiden van teams in diverse sectoren. Hij is gespecialiseerd in projectmanagement en teamdynamiek.',
            'communication_style' => 'Huub communiceert op een duidelijke en beknopte manier, met een focus op openheid en samenwerking. Hij moedigt teamleden aan om hun ideeën te delen en actief deel te nemen aan discussies.',
            'skillset' => 'Projectmanagement, Teamdynamiek, Agile Methodologieën, Conflictresolutie, Time Management',
            'deliverables' => 'Effectieve teamvergaderingen, Projectplannen, Risicobeheerstrategieën, Teamrapporten',
            'summary' => 'Ervaren teamleider met een passie voor het begeleiden van teams naar succes.',
            'team_id' => 1,
        ]);
        Teamleader::insert([
            'name' => 'Linda Jansen',
            'avatar_url' => 'https://i.pravatar.cc/150?img=4',
            'description' => 'Linda is een gedreven teamleider met een achtergrond in design en user experience. Ze begrijpt het belang van klantgericht denken en weet hoe ze teams kan inspireren om innovatieve oplossingen te creëren.',
            'communication_style' => 'Linda is een empathische communicator die waarde hecht aan feedback van teamleden. Ze gebruikt visuele hulpmiddelen om haar ideeën over te brengen en stimuleert creatief denken binnen het team.',
            'skillset' => 'Design Thinking, User Experience, Creatief Leiderschap, Team Motivatie, Klantgerichtheid',
            'deliverables' => 'Design Sprints, User Journey Maps, Prototypes, Klantfeedbacksessies',
            'summary' => 'Creatieve teamleider met een focus op klantgerichte oplossingen.',
            'team_id' => 1,
        ]);

        Teamleader::insert([
            'name' => 'Saskia de Vries',
            'avatar_url' => 'https://i.pravatar.cc/150?img=5',
            'description' => 'Saskia is een dynamische teamleider met een achtergrond in softwareontwikkeling. Ze begrijpt de technische uitdagingen waarmee teams worden geconfronteerd en weet hoe ze deze kunnen overwinnen.',
            'communication_style' => 'Saskia is een empathische luisteraar die waarde hecht aan feedback van teamleden. Ze communiceert op een ondersteunende en motiverende manier, waardoor teamleden zich gewaardeerd voelen.',
            'skillset' => 'Softwareontwikkeling, Teamcoaching, Probleemoplossing, Stakeholdermanagement, Scrum',
            'deliverables' => 'Sprintplannen, Retrospectieven, Teamontwikkelingsplannen, Stakeholderupdates',
            'summary' => 'Technisch onderlegde teamleider met een focus op teamcohesie en succes.',
            'team_id' => 1,
        ]);

        Teamleader::insert([
            'name' => 'Jeroen Bakker',
            'avatar_url' => 'https://i.pravatar.cc/150?img=7',
            'description' => 'Jeroen is een resultaatgerichte teamleider met een achtergrond in marketing en communicatie. Hij weet hoe hij teams kan inspireren om hun doelen te bereiken.',
            'communication_style' => 'Jeroen communiceert op een energieke en enthousiasmerende manier. Hij gebruikt storytelling om zijn visie over te brengen en teamleden te motiveren.',
            'skillset' => 'Marketingstrategie, Teammotivatie, Doelgerichtheid, Creatief Denken, Prestatiemanagement',
            'deliverables' => 'Marketingcampagnes, Teamdoelstellingen, Prestatiebeoordelingen, Creatieve brainstormsessies',
            'summary' => 'Inspirerende teamleider met een passie voor het behalen van resultaten.',
            'team_id' => 1,
        ]);

        //add the first 2 above teamleaders to the pivot table activity_teamleader for activity_id 1, the third to activity_id 2 and the fourth to activity_id 3
        DB::table('activity_teamleader')->insert([
            'activity_id' => 1,
            'teamleader_id' => 1,
        ]);
        DB::table('activity_teamleader')->insert([
            'activity_id' => 1,
            'teamleader_id' => 2,
        ]);
        DB::table('activity_teamleader')->insert([
            'activity_id' => 2,
            'teamleader_id' => 3,
        ]);
        DB::table('activity_teamleader')->insert([
            'activity_id' => 3,
            'teamleader_id' => 4,
        ]);

    }
}
