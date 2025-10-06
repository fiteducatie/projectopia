<div x-data="teamleaderComponent({{ $teamleader->id }})">
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
        </div>
        <div class="flex items-center gap-2">
            <!-- Notification indicator for pending schedule messages -->
            <div x-show="hasPendingMessage"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-75"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative">
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
            </div>

            <button type="button"
                    wire:click="startChat"
                    :class="hasPendingMessage ? 'bg-emerald-600 shadow-lg ring-2 ring-emerald-300 ring-opacity-50 animate-pulse' : 'bg-emerald-500'"
                    class="inline-flex items-center gap-2 px-3 py-2 rounded-md text-white text-sm hover:bg-emerald-600 transition-all duration-200 relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4a2 2 0 0 0-2 2v14l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/></svg>
                <span x-show="!hasPendingMessage">Chat</span>
                <span x-show="hasPendingMessage" class="font-semibold" x-text="buttonText"></span>
            </button>
        </div>
    </div>

    <script>
        function teamleaderComponent(teamleaderId) {
            return {
                hasPendingMessage: false,
                buttonText: 'Nieuw bericht!',

                init() {
                    this.checkForPendingMessage();
                    // Check every 30 seconds for new messages
                    setInterval(() => {
                        this.checkForPendingMessage();
                    }, 30000);

                    // Listen for when messages are marked as read
                    window.addEventListener('scheduleMessageRead', (event) => {
                        if (event.detail.teamleaderId === teamleaderId) {
                            this.hasPendingMessage = false;
                            this.buttonText = 'Nieuw bericht!'; // Reset to default
                        }
                    });
                },

                async checkForPendingMessage() {
                    try {
                        const response = await fetch(`/api/teamleader/${teamleaderId}/schedule/pending`);
                        const result = await response.json();

                        if (result.success && result.has_pending && result.schedule_data) {
                            // Check if any of the active schedule items have been read
                            const scheduleItems = result.schedule_data.items || [];
                            let unreadCount = 0;

                            // Check each schedule item individually
                            scheduleItems.forEach(item => {
                                const readKey = `teamleader_${teamleaderId}_schedule_${item.time_from}`;
                                const hasBeenRead = localStorage.getItem(readKey) === 'true';
                                if (!hasBeenRead) {
                                    unreadCount++;
                                }
                            });

                            this.hasPendingMessage = unreadCount > 0;

                            // Update button text based on unread count
                            this.updateButtonText(unreadCount);
                        } else {
                            this.hasPendingMessage = false;
                            // Clean up old localStorage entries when no schedule is active
                            this.cleanupOldScheduleEntries();
                        }
                    } catch (error) {
                        console.error('Error checking for pending messages:', error);
                    }
                },

                cleanupOldScheduleEntries() {
                    // Remove old schedule entries that are no longer relevant
                    const keysToRemove = [];
                    for (let i = 0; i < localStorage.length; i++) {
                        const key = localStorage.key(i);
                        if (key && key.startsWith(`teamleader_${teamleaderId}_schedule_`)) {
                            keysToRemove.push(key);
                        }
                    }

                    // Remove entries older than 24 hours
                    const oneDayAgo = Date.now() - (24 * 60 * 60 * 1000);
                    keysToRemove.forEach(key => {
                        try {
                            // Extract timestamp from key and check if it's old
                            const timestampMatch = key.match(/schedule_(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/);
                            if (timestampMatch) {
                                const scheduleTime = new Date(timestampMatch[1]).getTime();
                                if (scheduleTime < oneDayAgo) {
                                    localStorage.removeItem(key);
                                }
                            }
                        } catch (e) {
                            // If we can't parse the timestamp, remove the key anyway
                            localStorage.removeItem(key);
                        }
                    });
                },

                updateButtonText(activeCount) {
                    if (activeCount === 1) {
                        this.buttonText = 'Nieuw bericht!';
                    } else {
                        this.buttonText = `${activeCount} nieuwe berichten!`;
                    }
                }
            }
        }
    </script>
</div>
