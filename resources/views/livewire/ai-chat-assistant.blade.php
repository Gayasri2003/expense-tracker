<div>
    <!-- Floating Chat Button -->
    <button wire:click="toggleChat" class="fixed bottom-6 right-6 p-4 bg-gold-600 text-navy-950 rounded-full shadow-2xl hover:bg-gold-500 transition-transform transform hover:scale-110 z-50 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
        </svg>
    </button>

    <!-- Chat Window -->
    @if($isOpen)
        <div class="fixed bottom-24 right-6 w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden flex flex-col transform transition-all" style="height: 500px;">
            <!-- Header -->
            <div class="bg-navy-950 p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-gold-500 rounded-full text-navy-950">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm">AI Chat Assistant</h3>
                        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-widest">Online</p>
                    </div>
                </div>
                <button wire:click="toggleChat" class="text-navy-400 hover:text-white transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 p-4 overflow-y-auto bg-gray-50 flex flex-col gap-4">
                @foreach($messages as $msg)
                    @if($msg['type'] === 'ai')
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-full bg-navy-950 flex-shrink-0 flex items-center justify-center text-gold-500">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-sm text-navy-950">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-2 justify-end">
                            <div class="bg-gold-500 p-3 rounded-2xl rounded-tr-none shadow-sm text-sm text-navy-950 font-medium">
                                {{ $msg['text'] }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Input Area -->
            <div class="p-4 bg-white border-t border-gray-100">
                <form wire:submit.prevent="ask" class="flex gap-2">
                    <input wire:model="query" type="text" placeholder="Ask about your spending..." class="flex-1 bg-gray-50 border-transparent rounded-xl focus:border-gold-500 focus:ring-gold-500 text-sm py-2.5 px-4" required>
                    <button type="submit" class="p-2.5 bg-navy-950 text-white rounded-xl hover:bg-navy-900 transition-colors focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2" wire:loading.attr="disabled">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
