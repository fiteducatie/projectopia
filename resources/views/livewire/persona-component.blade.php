<div>
    <div class="rounded-lg border border-slate-200 p-3 flex items-center gap-3">
        @if ($persona->avatar_url)
            <img src="{{ $persona->avatar_url }}" alt="{{ $persona->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-200">
        @else
            <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-sm text-slate-700">
                {{ Str::of($persona->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
            </div>
        @endif
        <div class="flex-1">
            <div class="font-semibold">{{ $persona->role }}</div>
            <div class="text-slate-600 text-sm">{{ $persona->name }}</div>
        </div>
        <div class="flex items-center gap-2">
            @if($persona->project->status === 'closed')
                <button type="button" disabled class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-slate-300 text-slate-500 text-sm cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                    <span>Offline</span>
                </button>
            @else
                <button type="button" wire:click="startChat" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-sky-500 text-white text-sm hover:bg-sky-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                    <span>Chat</span>
                </button>
            @endif
        </div>
    </div>
</div>
