@extends('layouts.guest')

@section('title', $project->name . ' – Projectopia')

@section('content')
    <a href="{{ route('choose.team.projects', $project->team) }}" class="text-sky-600 hover:underline">← Terug naar
        projecten</a>

    <div class="mt-3 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">{{ $project->name }}</h1>
            <div class="mt-2 text-slate-600">Domein: {{ ucfirst($project->domain) }} · Complexiteit:
                {{ ucfirst($project->difficulty) }}</div>
        </div>
        @auth
            @if ($project->team && $project->team->owner_id === auth()->id())
                <a href="{{ url('/admin/' . $project->team->getRouteKey() . '/projects/' . $project->getRouteKey() . '/edit') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-amber-500 text-white text-sm hover:bg-amber-600">
                    Bewerken
                </a>
            @endif
        @endauth
    </div>
    @if ($project->getFirstMediaUrl('banner'))
        <div class="mt-4 rounded-xl overflow-hidden shadow-lg">
            <img src="{{ $project->getFirstMediaUrl('banner') }}" alt="{{ $project->name }} banner"
                class="w-full h-64 object-cover">
        </div>
    @endif


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
                        @livewire('persona-component', ['persona' => $persona], key('persona-' . $persona->id))
                    @empty
                        <div class="text-slate-500 text-sm">Geen betrokkenen toegevoegd.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
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
                <button onclick="closeModal('contextModal')"
                    class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
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
                <button onclick="closeModal('objectivesModal')"
                    class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
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
                <button onclick="closeModal('constraintsModal')"
                    class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
            </div>
        </div>
    </div>
    @livewire('persona-chat')
    @livewireScripts
    <script>
        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden')
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden')
        }
    </script>
@endsection
