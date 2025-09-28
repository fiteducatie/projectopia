<div>
    <div class="rounded-lg border border-slate-200 p-3 flex items-center gap-3">
        @if ($teamleader->avatar_url)
            <img src="{{ $teamleader->avatar_url }}" alt="{{ $teamleader->name }}" class="h-10 w-10 rounded-full object-cover border border-slate-200">
        @else
            <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-sm text-slate-700">
                {{ Str::of($teamleader->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
            </div>
        @endif
        <div class="flex-1">
            <div class="font-semibold">{{ $teamleader->name }}</div>
            <div class="text-slate-600 text-sm">{{ $teamleader->summary }}</div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" wire:click="startChat" class="inline-flex items-center gap-2 px-3 py-2 rounded-md bg-emerald-500 text-white text-sm hover:bg-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                <span>Chat</span>
            </button>
        </div>
    </div>
</div>
