<div>
    <div class="rounded-lg border border-slate-200 p-3">
        <div class="font-semibold">{{ $persona->role }}: {{ $persona->name }}</div>
        @if ($persona->goals)
            <div class="text-slate-600 text-sm mt-1">Doelen: {{ \Illuminate\Support\Str::limit(strip_tags($persona->goals), 120) }}</div>
        @endif
        <div class="mt-3">
            <button type="button" wire:click="startChat" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-sky-500 text-white text-sm hover:bg-sky-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                <span>Chat</span>
            </button>
            <button onclick="openModal('personaModal{{ $persona->id }}')" class="ml-2 inline-flex items-center gap-2 px-3 py-2 rounded-md border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">Meer info</button>
        </div>
    </div>
</div>
