<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col min-w-0">
            <h2 class="font-extrabold text-lg sm:text-2xl text-navy-950 leading-tight font-outfit uppercase tracking-tight">
                {{ __('Account Settings') }}
            </h2>
            <div class="flex items-center mt-1 max-w-[200px] sm:max-w-none">
                <span class="text-[10px] font-bold text-navy-400 uppercase tracking-[0.2em] leading-relaxed">Manage your profile and security preferences</span>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-2">
                    @livewire('profile.update-password-form')
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
