<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kies team – Projectopia</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="max-w-6xl mx-auto px-6 py-10">
        <h1 class="text-2xl font-bold mb-6">Kies een team</h1>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse ($teams as $team)
                <a href="{{ route('choose.team.projects', $team) }}" class="block rounded-xl border border-slate-200 bg-white p-6 hover:shadow-md">
                    <div class="text-lg font-semibold">{{ $team->name }}</div>
                    <div class="mt-1 text-slate-600 text-sm">{{ $team->projects_count }} projecten</div>
                </a>
            @empty
                <div class="col-span-full text-slate-500">Nog geen teams beschikbaar.</div>
            @endforelse
        </div>
    </div>
</body>
</html>


