<div>
    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="absolute inset-0 bg-black/60" wire:click="close"></div>
            <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden"
                 x-data="teamleaderChat({{ $teamleaderId }})">

                <!-- Header -->
                <header class="flex items-center gap-3 px-6 py-4 bg-emerald-50 border-b border-emerald-200">
                    <button wire:click="close" class="p-2 hover:bg-emerald-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                    </button>
                    @if($teamleader)
                        @if($teamleader->avatar_url)
                            <img src="{{ $teamleader->avatar_url }}" alt="{{ $teamleader->name }}" class="h-12 w-12 rounded-full border border-emerald-200 object-cover">
                        @else
                            <div class="h-12 w-12 rounded-full bg-emerald-200 flex items-center justify-center text-lg font-semibold text-emerald-700">
                                {{ Str::of($teamleader->name)->explode(' ')->map(fn($p)=>Str::substr($p,0,1))->take(2)->implode('') }}
                            </div>
                        @endif
                        <div class="leading-tight">
                            <div class="font-bold text-lg text-emerald-900">{{ $teamleader->name }}</div>
                            <div class="text-sm text-emerald-600">
                                @if($this->isActivityClosed())
                                    <span class="text-red-500">Momenteel offline</span>
                                @else
                                    <span x-text="isTyping ? 'typing...' : 'Team Leider'"></span>
                                @endif
                            </div>
                        </div>
                    @else
                        <h2 class="font-bold text-lg text-emerald-900">Team Leider Chat</h2>
                    @endif

                    <!-- Schedule History Button -->
                    @if($teamleader)
                        <button onclick="openScheduleHistory('{{ $teamleader->activities()->first()->slug ?? '' }}')"
                                class="ml-auto inline-flex items-center gap-2 px-3 py-1.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Alle team updates
                        </button>
                    @endif
                </header>

                <!-- Chat Messages -->
                <main class="flex-1 p-6 space-y-4 min-h-[400px] max-h-[500px] overflow-y-auto bg-slate-50 chat-messages"
                      x-ref="messagesContainer"
                      data-teamleader-chat
                      data-teamleader-id="{{ $teamleaderId }}">
                    <template x-for="(message, index) in messages" :key="index">
                        <div :class="message.type === 'outgoing' ? 'flex justify-end' : 'flex'">
                            <div :class="{
                                'bg-emerald-500 text-white rounded-lg px-4 py-3 shadow max-w-[80%]': message.type === 'outgoing',
                                'bg-white border border-slate-200 text-slate-900 rounded-lg px-4 py-3 shadow max-w-[80%]': message.type === 'incoming' && !message.isScheduleMessage,
                                'bg-blue-100 border-2 border-blue-300 text-blue-900 rounded-lg px-4 py-3 shadow max-w-[80%]': message.type === 'incoming' && message.isScheduleMessage,
                                'animate-pulse': message.streaming,
                                'bg-red-500 text-white': message.error
                            }"
                            :data-schedule-message="message.isScheduleMessage ? 'true' : 'false'">
                                <!-- Typing indicator for empty streaming messages -->
                                <div x-show="message.streaming && !message.text" class="flex items-center space-x-1">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                </div>

                                <!-- Message text -->
                                <div x-show="!message.streaming || message.text">
                                    <div class="text-sm"
                                         x-html="parseMarkdown(message.text)">
                                        <span x-show="message.streaming" class="inline-block w-2 h-4 bg-slate-400 animate-pulse ml-1"></span>
                                    </div>
                                    <span class="block text-right text-xs mt-1 opacity-70"
                                          :class="message.type === 'outgoing' ? 'text-emerald-100' : 'text-slate-500'"
                                          x-text="message.time"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </main>

                <!-- Chat Input -->
                <footer class="p-4 bg-white border-t border-slate-200">
                    @if($this->isActivityClosed())
                        <div class="flex items-center justify-center gap-2 text-slate-400 text-sm">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span>Activiteit is gesloten - chat niet beschikbaar</span>
                        </div>
                    @else
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <input
                                x-model="currentMessage"
                                type="text"
                                placeholder="Type je bericht..."
                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                :disabled="isTyping"
                                required
                            >
                            <button
                                type="submit"
                                class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="isTyping || !currentMessage.trim()"
                            >
                                <div x-show="isTyping" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                <svg x-show="!isTyping" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                                </svg>
                            </button>
                        </form>
                    @endif
                </footer>
            </div>
        </div>
    @endif

    <script>
        function teamleaderChat(teamleaderId) {
            return {
                teamleaderId: teamleaderId,
                messages: [],
                currentMessage: '',
                isTyping: false,
                currentStreamingIndex: null,

                addMessage(type, text, streaming = false) {
                    const message = {
                        type: type,
                        text: text,
                        time: this.getCurrentTime(),
                        streaming: streaming
                    };

                    this.messages.push(message);
                    this.scrollToBottom();
                    return this.messages.length - 1;
                },

                updateMessage(index, text) {
                    if (this.messages[index]) {
                        this.messages[index].text = text;
                    }
                },

                completeMessage(index) {
                    if (this.messages[index]) {
                        this.messages[index].streaming = false;
                        this.messages[index].time = this.getCurrentTime();
                    }
                },

                markMessageAsError(index, errorText) {
                    if (this.messages[index]) {
                        this.messages[index].text = errorText;
                        this.messages[index].error = true;
                        this.messages[index].streaming = false;
                    }
                },

                async init() {
                    // Load existing messages including schedule messages
                    await this.loadMessages();
                    // Mark schedule messages as read when chat is opened
                    await this.markAsRead();
                },

                async loadMessages() {
                    try {
                        const response = await fetch(`/api/teamleader/${this.teamleaderId}/messages`);
                        if (response.ok) {
                            const messages = await response.json();
                            this.messages = messages.map(msg => ({
                                type: msg.sender === 'user' ? 'outgoing' : 'incoming',
                                text: msg.message,
                                time: new Date(msg.timestamp).toLocaleTimeString('en-GB', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }),
                                streaming: false,
                                isScheduleMessage: msg.is_schedule_message || false
                            }));
                            this.scrollToBottom();
                        }
                    } catch (error) {
                        console.error('Error loading messages:', error);
                    }
                },

                async markAsRead() {
                    // Mark schedule messages as read in localStorage when chat is opened
                    try {
                        // Get current schedule data to mark as read
                        const response = await fetch(`/api/teamleader/${this.teamleaderId}/schedule/pending`);
                        const result = await response.json();

                        if (result.success && result.has_pending && result.schedule_data) {
                            const scheduleItems = result.schedule_data.items || [];

                            // Mark each schedule item as read individually
                            scheduleItems.forEach(item => {
                                const readKey = `teamleader_${this.teamleaderId}_schedule_${item.time_from}`;
                                localStorage.setItem(readKey, 'true');
                            });

                            // Trigger a re-check in the teamleader component to update button state
                            window.dispatchEvent(new CustomEvent('scheduleMessageRead', {
                                detail: {
                                    teamleaderId: this.teamleaderId,
                                    scheduleItems: scheduleItems.map(item => item.time_from)
                                }
                            }));
                        }
                    } catch (error) {
                        console.error('Error marking messages as read:', error);
                    }
                },

                async sendMessage() {
                    if (!this.currentMessage.trim()) return;

                    // Check if project is closed
                    if ({{ $this->isActivityClosed() ? 'true' : 'false' }}) {
                        return;
                    }

                    // Add user message
                    this.addMessage('outgoing', this.currentMessage);
                    const userMessage = this.currentMessage;
                    this.currentMessage = '';

                    // Add streaming response placeholder
                    this.currentStreamingIndex = this.addMessage('incoming', '', true);
                    this.isTyping = true;

                    try {
                        await this.streamResponse(userMessage);
                    } catch (error) {
                        console.error('Chat error:', error);
                        this.markMessageAsError(this.currentStreamingIndex, 'Something went wrong. Please try again.');
                    } finally {
                        this.isTyping = false;
                        this.currentStreamingIndex = null;
                    }
                },

                async streamResponse(userMessage) {
                    // Prepare conversation history for API
                    const conversationMessages = this.messages
                        .filter(msg => !msg.streaming && !msg.error)
                        .map(msg => ({
                            role: msg.type === 'outgoing' ? 'user' : 'assistant',
                            content: msg.text
                        }));

                    // Add the current user message
                    conversationMessages.push({
                        role: 'user',
                        content: userMessage
                    });

                    const url = `/api/teamleader/${this.teamleaderId}/chat/stream`;

                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            messages: conversationMessages
                        })
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    let fullResponse = '';

                    try {
                        while (true) {
                            const { done, value } = await reader.read();
                            if (done) break;

                            const chunk = decoder.decode(value);
                            const lines = chunk.split('\n');

                            for (const line of lines) {
                                if (line.startsWith('data: ')) {
                                    const data = line.slice(6).trim();
                                    if (data === '[DONE]') {
                                        this.completeMessage(this.currentStreamingIndex);
                                        return;
                                    }

                                    try {
                                        const parsed = JSON.parse(data);

                                        if (parsed.type === 'content') {
                                            fullResponse += parsed.content;
                                            this.updateMessage(this.currentStreamingIndex, fullResponse);
                                            this.scrollToBottom();
                                        } else if (parsed.type === 'done') {
                                            this.completeMessage(this.currentStreamingIndex);
                                            return;
                                        } else if (parsed.type === 'error') {
                                            throw new Error(parsed.message || 'Unknown error');
                                        }
                                    } catch (parseError) {
                                        if (data !== '' && data !== '[DONE]') {
                                            console.warn('Failed to parse SSE data:', data);
                                        }
                                    }
                                }
                            }
                        }
                    } finally {
                        reader.releaseLock();
                    }
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.messagesContainer;
                        container.scrollTop = container.scrollHeight;
                    });
                },

                getCurrentTime() {
                    return new Date().toLocaleTimeString('en-GB', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },

                parseMarkdown(text) {
                    if (!text) return '';

                    // Simple markdown parser for basic formatting
                    return text
                        // Bold text **text** or __text__
                        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                        .replace(/__(.*?)__/g, '<strong>$1</strong>')
                        // Italic text *text* or _text_
                        .replace(/\*(.*?)\*/g, '<em>$1</em>')
                        .replace(/_(.*?)_/g, '<em>$1</em>')
                        // Line breaks
                        .replace(/\n/g, '<br>')
                        // Links [text](url)
                        .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-blue-600 underline" target="_blank">$1</a>');
                },

                // Initialize when component loads
                mounted() {
                    this.init();
                }
            }
        }

    </script>
</div>
