<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col min-w-0">
            <h2 class="font-extrabold text-lg sm:text-2xl text-navy-950 leading-tight font-outfit uppercase tracking-tight">
                {{ __('Financial Overview') }}
            </h2>
            <div class="flex items-center mt-0.5 max-w-[200px] sm:max-w-none">
                <span class="text-[9px] sm:text-[10px] font-bold text-navy-400 uppercase tracking-[0.2em] leading-relaxed">{{ now()->format('l, d F Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen px-4 sm:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('dashboard-stats')
        </div>
    </div>
</x-app-layout>
