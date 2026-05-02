<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col min-w-0">
            <h2 class="font-extrabold text-lg sm:text-2xl text-navy-950 leading-tight font-outfit uppercase tracking-tight">
                {{ __('Loans & Leases') }}
            </h2>
            <div class="flex items-center mt-1 max-w-[200px] sm:max-w-none">
                <span class="text-[10px] font-bold text-navy-400 uppercase tracking-[0.2em] leading-relaxed">Manage your liabilities and loan repayments</span>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl rounded-[3rem] border border-gray-100">
                @livewire('loan-manager')
            </div>
        </div>
    </div>
</x-app-layout>
