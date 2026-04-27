<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-2 text-navy-400 hover:text-navy-950 transition-colors focus:outline-none">
        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-2 right-2 size-2 bg-red-500 rounded-full border-2 border-white"></span>
        @endif
    </button>

    <div x-show="open" @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-2 w-80 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xs font-bold text-navy-950 uppercase tracking-widest">Notifications</h3>
            @if($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-[10px] font-bold text-gold-600 hover:text-gold-700 uppercase tracking-widest">Mark all read</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $n)
                <div class="px-6 py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors relative group {{ $n->is_read ? 'opacity-60' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="size-2 mt-1.5 rounded-full shrink-0 {{ $n->type === 'danger' ? 'bg-red-500' : ($n->type === 'warning' ? 'bg-amber-500' : 'bg-blue-500') }}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-navy-950 truncate">{{ $n->title }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5 line-clamp-2">{{ $n->message }}</p>
                            <p class="text-[9px] text-navy-300 font-bold uppercase tracking-widest mt-2">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if(!$n->is_read)
                        <button wire:click="markAsRead({{ $n->id }})" class="absolute top-4 right-4 text-[9px] font-bold text-navy-400 hover:text-navy-950 opacity-0 group-hover:opacity-100 transition-opacity uppercase tracking-widest">Read</button>
                    @endif
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-xs text-navy-300 italic">No notifications yet.</p>
                </div>
            @endforelse
        </div>

        <div class="px-6 py-3 bg-gray-50/50 text-center">
            <span class="text-[10px] font-bold text-navy-400 uppercase tracking-widest">Stay Updated</span>
        </div>
    </div>
</div>
