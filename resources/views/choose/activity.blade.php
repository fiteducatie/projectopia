@extends('layouts.guest')

@section('title', $activity->name . ' – Projectopia')

@section('content')
    <a href="{{ route('choose.team.activities', $activity->team) }}" class="text-sky-600 hover:underline">← Terug naar
        activiteiten</a>

    <div class="mt-3 flex items-start justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">{{ $activity->name }}</h1>
            <div class="mt-2 text-slate-600">Domein: {{ ucfirst($activity->domain) }} · Complexiteit:
                {{ ucfirst($activity->difficulty) }}</div>
        </div>
        @auth
            @if ($activity->team && $activity->team->owner_id === auth()->id())
                <div class="flex items-center gap-2">
                    <a href="{{ url('/admin/' . $activity->team->getRouteKey() . '/activities/' . $activity->getRouteKey() . '/edit') }}"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-amber-500 text-white text-sm hover:bg-amber-600">
                        Bewerken
                    </a>
                    @if (auth()->user()->hasRole('Admin'))
                        <form method="POST" action="{{ route('activity.toggle-status', $activity) }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-white text-sm {{ $activity->status === 'closed' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700' }}"
                                onclick="return confirm('Weet je zeker dat je deze activiteit wilt {{ $activity->status === 'closed' ? 'heropenen' : 'sluiten' }}?')">
                                {{ $activity->status === 'closed' ? 'Heropen activiteit' : 'Sluit activiteit' }}
                            </button>
                        </form>
                    @endif
                </div>
            @endif
        @endauth
    </div>
    @if ($activity->getFirstMediaUrl('banner'))
        <div class="mt-4 rounded-xl overflow-hidden shadow-lg">
            <img src="{{ $activity->getFirstMediaUrl('banner') }}" alt="{{ $activity->name }} banner"
                class="w-full h-64 object-contain">
        </div>
    @endif


    <div class="mt-6 grid md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="font-semibold mb-2">Context</h2>
                <div class="prose prose-slate max-w-none text-sm">{!! $activity->context !!}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="font-semibold mb-2">Doelstellingen</h2>
                <div class="prose prose-slate max-w-none text-sm">{!! $activity->objectives !!}</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="font-semibold mb-2">Randvoorwaarden</h2>
                <div class="prose prose-slate max-w-none text-sm">{!! $activity->constraints !!}</div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h3 class="font-semibold mb-3">Teamleiders</h3>
                <div class="space-y-3">
                    @forelse ($activity->teamleaders as $teamleader)
                        @livewire('teamleader-component', ['teamleader' => $teamleader], key('teamleader-' . $teamleader->id))
                    @empty
                        <div class="text-slate-500 text-sm">Geen teamleiders toegevoegd.</div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-6">
                <h3 class="font-semibold mb-3">Betrokkenen</h3>
                <div class="space-y-3">
                    @forelse ($activity->personas as $persona)
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
                {!! $activity->context !!}
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
                {!! $activity->objectives !!}
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
                {!! $activity->constraints !!}
            </div>
            <div class="mt-4 text-right">
                <button onclick="closeModal('constraintsModal')"
                    class="px-4 py-2 rounded-md border border-slate-200">Sluiten</button>
            </div>
        </div>
    </div>
    @livewire('persona-chat')
    @livewire('teamleader-chat')
    @livewireScripts
    <script>
        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden')
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden')
        }

        // Initialize schedule notifications for teamleaders
        document.addEventListener('DOMContentLoaded', function() {
            @foreach($activity->teamleaders as $teamleader)
                new ScheduleNotification({{ $teamleader->id }}, document.querySelector('[data-teamleader-chat]'));
            @endforeach
        });

        // Simplified Schedule History functionality
        async function openScheduleHistory(activitySlug) {
            const modal = document.getElementById('simple-schedule-history-modal');
            modal.classList.remove('hidden');

            await loadSimpleScheduleHistory(activitySlug);
        }

        async function loadSimpleScheduleHistory(activitySlug) {
            const content = document.getElementById('simple-schedule-history-content');
            content.innerHTML = `
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div>
                    <p class="mt-2 text-gray-600 text-sm">Laden...</p>
                </div>
            `;

            try {
                const response = await fetch(`/api/schedule/activity/${activitySlug}/history`);
                const result = await response.json();

                if (result.success) {
                    const scheduleData = result.data;
                    // Filter to show only active and completed items
                    const filteredData = scheduleData.filter(item =>
                        item.status === 'active' || item.status === 'completed'
                    );

                    if (filteredData.length === 0) {
                        content.innerHTML = `
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600">Geen schedule items gevonden</p>
                            </div>
                        `;
                        return;
                    }

                    content.innerHTML = filteredData.map(item => renderSimpleScheduleItem(item)).join('');
                } else {
                    throw new Error('Failed to load history data');
                }
            } catch (error) {
                console.error('Error loading schedule history:', error);
                content.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-600">Er is een fout opgetreden</p>
                    </div>
                `;
            }
        }

        function renderSimpleScheduleItem(item) {
            const statusColors = {
                'completed': 'bg-green-100 text-green-800',
                'active': 'bg-purple-100 text-purple-800'
            };

            const statusLabels = {
                'completed': 'Voltooid',
                'active': 'Actief'
            };

            return `
                <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:shadow-sm transition-shadow">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-gray-900">${item.title}</h3>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-600">
                                <span>📅 ${item.time_from_formatted} - ${item.time_until_formatted}</span>
                                ${item.days_ago ? `<span>${item.days_ago} dagen geleden</span>` : ''}
                            </div>
                        </div>
                        <span class="px-2 py-1 rounded-full text-xs font-medium ${statusColors[item.status]}">
                            ${statusLabels[item.status]}
                        </span>
                    </div>

                    <p class="text-gray-700 text-sm">${item.description}</p>
                </div>
            `;
        }

    </script>

    <script src="{{ asset('js/components/ScheduleNotification.js') }}"></script>

    <!-- Simple Schedule History Modal -->
    <div id="simple-schedule-history-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 hidden">
        <div class="absolute inset-0 bg-black/60" onclick="document.getElementById('simple-schedule-history-modal').classList.add('hidden')"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <header class="flex items-center justify-between px-6 py-4 bg-blue-50 border-b border-blue-200">
                <h2 class="text-lg font-bold text-blue-900">Schedule History</h2>
                <button onclick="document.getElementById('simple-schedule-history-modal').classList.add('hidden')"
                        class="p-2 hover:bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </header>

            <!-- Content -->
            <main class="p-6 max-h-80 overflow-y-auto">
                <div id="simple-schedule-history-content">
                    <div class="text-center py-8">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500 mx-auto"></div>
                        <p class="mt-2 text-gray-600 text-sm">Laden...</p>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-end">
                    <button onclick="document.getElementById('simple-schedule-history-modal').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                        Sluiten
                    </button>
                </div>
            </footer>
        </div>
    </div>
    <div>
        @livewire('show-info-popup')
    </div>
    
@endsection
