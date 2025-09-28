<div>
    <!-- Modal -->
    @if($isOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="relative w-[360px] h-[640px] bg-slate-900 text-slate-100 rounded-[36px] shadow-2xl overflow-hidden"
                 x-data="personaChat({{ $personaId }})">

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
                            <div class="text-[11px] text-white/80">
                                @if($this->isProjectClosed())
                                    <span class="text-red-300">Momenteel offline</span>
                                @else
                                    <span x-text="isTyping ? 'typing...' : '{{ $persona->role }}'"></span>
                                @endif
                            </div>
                        </div>
                    @else
                        <h2 class="font-semibold">Chat</h2>
                    @endif
                </header>

                <!-- Messages -->
                <main class="h-[calc(100%-120px)] overflow-y-auto bg-slate-800 p-3 space-y-2"
                      x-ref="messagesContainer">
                    <template x-for="(message, index) in messages" :key="index">
                        <div :class="message.type === 'outgoing' ? 'flex justify-end' : 'flex'">
                            <div :class="{
                                'bg-[#005C4B] text-white rounded-2xl px-3 py-2 shadow max-w-[80%]': message.type === 'outgoing',
                                'bg-slate-700 text-white rounded-2xl px-3 py-2 shadow max-w-[80%]': message.type === 'incoming',
                                'animate-pulse': message.streaming,
                                'bg-red-600': message.error
                            }">
                                <!-- Typing indicator for empty streaming messages -->
                                <div x-show="message.streaming && !message.text" class="flex items-center space-x-1">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-slate-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                </div>

                                <!-- Message text -->
                                <div x-show="!message.streaming || message.text">
                                    <p class="text-sm">
                                        <span x-text="message.text"></span>
                                        <span x-show="message.streaming" class="inline-block w-2 h-4 bg-slate-300 animate-pulse ml-1"></span>
                                    </p>
                                    <span class="block text-right text-xs"
                                          :class="message.type === 'outgoing' ? 'text-emerald-100/90' : 'text-slate-300'"
                                          x-text="message.time"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </main>

                <!-- Input -->
                <footer class="absolute bottom-0 left-0 right-0 bg-slate-900 px-2 py-2">
                    @if($this->isProjectClosed())
                        <div class="flex items-center justify-center gap-2 text-slate-400 text-sm">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span>Project is gesloten - chat niet beschikbaar</span>
                        </div>
                    @else
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <input type="text"
                                   x-model="currentMessage"
                                   placeholder="Schrijf een bericht"
                                   class="flex-1 rounded-2xl bg-slate-800 px-4 py-2 text-sm outline-none"
                                   :disabled="isTyping">
                            <button type="submit"
                                    class="p-3 rounded-full bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                    :disabled="isTyping || !currentMessage.trim()">
                                <div x-show="isTyping" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                <span x-show="!isTyping">➤</span>
                            </button>
                        </form>
                    @endif
                </footer>
            </div>
        </div>
    @endif

    <script>
        function personaChat(personaId) {
            return {
                personaId: personaId,
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

                async sendMessage() {
                    if (!this.currentMessage.trim()) return;

                    // Check if project is closed
                    if ({{ $this->isProjectClosed() ? 'true' : 'false' }}) {
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

                    const url = `/api/persona/${this.personaId}/chat/stream`;

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
                }
            }
        }
    </script>
</div>
