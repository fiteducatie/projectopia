<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Team;
use Illuminate\Console\Command;

class ExportDataSeeder extends Command
{
    protected $signature = 'app:export-data-seeder {name=ExportedDataSeeder}';

    protected $description = 'Export current Teams, Projects, Personas to a database seeder';

    public function handle(): int
    {
        $name = preg_replace('/[^A-Za-z0-9_]/', '', $this->argument('name')) ?: 'ExportedDataSeeder';
        $class = ucfirst($name);
        $path = database_path('seeders/'.$class.'.php');

        $this->info('Collecting data...');
        $teams = Team::with(['projects.personas'])->orderBy('id')->get();

        $export = [
            'users' => [],
            'teams' => [],
            'projects' => [],
            'personas' => [],
        ];

        $ownerIds = $teams->pluck('owner_id')->filter()->unique()->values();
        if ($ownerIds->isNotEmpty()) {
            $users = \App\Models\User::query()->whereIn('id', $ownerIds)->get();
            foreach ($users as $user) {
                $export['users'][] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // Note: Do not export passwords in plain text; if needed, ensure hashed values
                    'password' => $user->getAttributes()['password'] ?? null,
                    'created_at' => (string) $user->created_at,
                    'updated_at' => (string) $user->updated_at,
                ];
            }
        }

        foreach ($teams as $team) {
            $export['teams'][] = [
                'id' => $team->id,
                'name' => $team->name,
                'owner_id' => $team->owner_id,
                'settings' => $team->settings,
                'created_at' => (string) $team->created_at,
                'updated_at' => (string) $team->updated_at,
            ];

            foreach ($team->projects as $project) {
                $export['projects'][] = [
                    'id' => $project->id,
                    'team_id' => $project->team_id,
                    'name' => $project->name,
                    'domain' => $project->domain,
                    'context' => $project->context,
                    'objectives' => $project->objectives,
                    'constraints' => $project->constraints,
                    'start_date' => optional($project->start_date)->toDateString(),
                    'end_date' => optional($project->end_date)->toDateString(),
                    'risk_notes' => $project->risk_notes,
                    'difficulty' => $project->difficulty,
                    'created_at' => (string) $project->created_at,
                    'updated_at' => (string) $project->updated_at,
                ];

                foreach ($project->personas as $persona) {
                    $export['personas'][] = [
                        'id' => $persona->id,
                        'project_id' => $persona->project_id,
                        'name' => $persona->name,
                        'role' => $persona->role,
                        'avatar_url' => $persona->avatar_url,
                        'goals' => $persona->goals,
                        'traits' => $persona->traits,
                        'communication_style' => $persona->communication_style,
                        'created_at' => (string) $persona->created_at,
                        'updated_at' => (string) $persona->updated_at,
                    ];
                }
            }
        }

        $php = var_export($export, true);

        $template = <<<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class {{CLASS}} extends Seeder
{
    public function run(): void
    {
        $data = {{DATA}};

        DB::beginTransaction();
        try {
            // Disable FKs for insert order flexibility
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            }

            // Optional: clear tables to avoid key conflicts
            // DB::table('personas')->delete();
            // DB::table('projects')->delete();
            // DB::table('teams')->delete();
            // DB::table('users')->delete();

            if (!empty($data['users'])) {
                DB::table('users')->upsert($data['users'], ['id']);
            }
            if (!empty($data['teams'])) {
                DB::table('teams')->upsert($data['teams'], ['id']);
            }
            if (!empty($data['projects'])) {
                DB::table('projects')->upsert($data['projects'], ['id']);
            }
            if (!empty($data['personas'])) {
                DB::table('personas')->upsert($data['personas'], ['id']);
            }

            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
PHP;

        $output = str_replace(['{{CLASS}}', '{{DATA}}'], [$class, $php], $template);
        file_put_contents($path, $output);

        $this->info("Seeder written to: {$path}");
        $this->info("Run with: php artisan db:seed --class=Database\\Seeders\\{$class}");

        return self::SUCCESS;
    }
}


