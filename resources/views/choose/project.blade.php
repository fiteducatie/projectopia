<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->name }} – Projectopia</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="max-w-4xl mx-auto px-6 py-10">
        <a href="{{ route('choose.team.projects', $project->team) }}" class="text-sky-600 hover:underline">← Terug naar projecten</a>
        <div class="mt-3 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">{{ $project->name }}</h1>
                <div class="mt-2 text-slate-600">Domein: {{ ucfirst($project->domain) }} · Moeilijkheid: {{ ucfirst($project->difficulty) }}</div>
            </div>
            @auth
                @if ($project->team && $project->team->owner_id === auth()->id())
                    <a href="{{ url('/admin/'.$project->team->getRouteKey().'/projects/'.$project->getRouteKey().'/edit') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-amber-500 text-white text-sm hover:bg-amber-600">
                        Bewerken
                    </a>
                @endif
            @endauth
        </div>

        <div class="mt-6 grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="font-semibold mb-2">Context</h2>
                    <div class="prose prose-slate max-w-none text-sm">{!! $project->context !!}</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="font-semibold mb-2">Doelstellingen</h2>
                    <div class="prose prose-slate max-w-none text-sm">{!! $project->objectives !!}</div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h2 class="font-semibold mb-2">Randvoorwaarden</h2>
                    <div class="prose prose-slate max-w-none text-sm">{!! $project->constraints !!}</div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold mb-3">Betrokkenen</h3>
                    <div class="space-y-3">
                        @forelse ($project->personas as $persona)
                            <div class="rounded-lg border border-slate-200 p-3">
                                <div class="font-semibold">{{ $persona->role }}: {{ $persona->name }}</div>
                                @if ($persona->goals)
                                    <div class="text-slate-600 text-sm mt-1">Doelen: {{ \Illuminate\Support\Str::limit(strip_tags($persona->goals), 120) }}</div>
                                @endif
                                <div class="mt-3">
                                    <a href="{{ url('/kies-project/personas/'.$persona->id.'/chat') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-sky-500 text-white text-sm hover:bg-sky-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                                        <span>Chat</span>
                                    </a>
                                    <button onclick="openModal('personaModal{{ $persona->id }}')" class="ml-2 inline-flex items-center gap-2 px-3 py-2 rounded-md border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">Meer info</button>
                                </div>
                            </div>
                        @empty
                            <div class="text-slate-500 text-sm">Geen betrokkenen toegevoegd.</div>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <h3 class="font-semibold mb-2">Periode</h3>
                    <div class="text-slate-700 text-sm">{{ optional($project->start_date)->format('d-m-Y') }} – {{ optional($project->end_date)->format('d-m-Y') }}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div id="contextModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('contextModal')"></div>
        <div class="relative w-full max-w-2xl rounded-xl bg-white p-6 shadow-lg max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">Context</h3>
                <button onclick="closeModal('contextModal')" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>
            <div class="prose prose-slate max-w-none">
                {!! $project->context !!}
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal('contextModal')" class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
            </div>
        </div>
    </div>

    <div id="objectivesModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('objectivesModal')"></div>
        <div class="relative w-full max-w-2xl rounded-xl bg-white p-6 shadow-lg max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">Doelstellingen</h3>
                <button onclick="closeModal('objectivesModal')" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>
            <div class="prose prose-slate max-w-none">
                {!! $project->objectives !!}
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal('objectivesModal')" class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
            </div>
        </div>
    </div>

    <div id="constraintsModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/40" onclick="closeModal('constraintsModal')"></div>
        <div class="relative w-full max-w-2xl rounded-xl bg-white p-6 shadow-lg max-h-[80vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-lg">Randvoorwaarden</h3>
                <button onclick="closeModal('constraintsModal')" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>
            <div class="prose prose-slate max-w-none">
                {!! $project->constraints !!}
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal('constraintsModal')" class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id){ document.getElementById(id)?.classList.remove('hidden') }
        function closeModal(id){ document.getElementById(id)?.classList.add('hidden') }
        function openChat(id, name, role){
            const root = document.getElementById('personaChat');
            const header = document.getElementById('personaChatHeader');
            header.textContent = `Chat met ${role} – ${name}`;
            root.classList.remove('hidden');
            root.classList.add('flex');
        }
        function closeChat(){
            const root = document.getElementById('personaChat');
            root.classList.add('hidden');
            root.classList.remove('flex');
        }
        function sendChat(){
            const input = document.getElementById('personaChatInput');
            const container = document.querySelector('#personaChatBody .p-4');
            const text = input.value.trim();
            if(!text) return;
            const mine = document.createElement('div');
            mine.className = 'flex justify-end';
            mine.innerHTML = `<div class=\"max-w-[80%] rounded-2xl rounded-br-sm bg-sky-500 text-white px-3 py-2\">${text}</div>`;
            container.appendChild(mine);
            input.value='';
            document.getElementById('personaChatBody').scrollTop = 1e6;
        }
    </script>
    @livewire('persona-chat')
    @livewireScripts
</body>
</html>


