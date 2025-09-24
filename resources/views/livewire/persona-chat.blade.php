<div>
    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="relative w-[360px] h-[640px] bg-slate-900 text-slate-100 rounded-[36px] shadow-2xl overflow-hidden">

                <!-- Header -->
                <header class="flex items-center gap-3 px-3 py-3 bg-[#075E54]">
                    <button wire:click="close" class="p-2">←</button>
                    @if($persona)
                        @if($persona->avatar_url)
                            <img src="{{ $persona->avatar_url }}" alt="{{ $persona->name }}" class="h-10 w-10 rounded-full border border-white/20 object-cover">
                        @else
                            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-sm">{{ Str::of($persona->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}</div>
                        @endif
                        <div class="leading-tight">
                            <div class="font-semibold text-sm">{{ $persona->name }}</div>
                            <div class="text-[11px] text-white/80">{{ $persona->role }}</div>
                        </div>
                    @else
                        <h2 class="font-semibold">Chat</h2>
                    @endif
                </header>

                <!-- Messages -->
                <main class="h-[calc(100%-120px)] overflow-y-auto bg-slate-800 p-3 space-y-2">
                    @foreach($messages as $msg)
                        @if($msg['type'] === 'outgoing')
                            <div class="flex justify-end">
                                <div class="bg-[#005C4B] text-white rounded-2xl px-3 py-2 shadow max-w-[80%]">
                                    <p class="text-sm">{{ $msg['text'] }}</p>
                                    <span class="block text-right text-xs text-emerald-100/90">{{ $msg['time'] }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex">
                                <div class="bg-slate-700 text-white rounded-2xl px-3 py-2 shadow max-w-[80%]">
                                    <p class="text-sm">{{ $msg['text'] }}</p>
                                    <span class="block text-right text-xs text-slate-300">{{ $msg['time'] }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </main>

                <!-- Input -->
                <footer class="absolute bottom-0 left-0 right-0 bg-slate-900 px-2 py-2">
                    <form wire:submit.prevent="send" class="flex gap-2">
                        <input type="text"
                               wire:model.defer="message"
                               placeholder="Schrijf een bericht"
                               class="flex-1 rounded-2xl bg-slate-800 px-4 py-2 text-sm outline-none">
                        <button type="submit"
                                class="p-3 rounded-full bg-emerald-600 hover:bg-emerald-700">
                            ➤
                        </button>
                    </form>
                </footer>
            </div>
        </div>
    @endif
</div>
