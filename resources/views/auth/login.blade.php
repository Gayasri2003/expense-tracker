<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="bg-navy-950 border-navy-800 text-gold-600 focus:ring-gold-500" />
                    <span class="ms-2 text-sm text-navy-400">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="underline text-sm text-navy-400 hover:text-white rounded-md focus:outline-none transition" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-10 flex flex-col items-center space-y-6">
                <x-button>
                    {{ __('Log in') }}
                </x-button>

                <div class="flex flex-col items-center space-y-3">
                    <div class="text-navy-500 text-sm">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="text-gold-500 hover:text-gold-400 font-bold ml-1 transition">
                            {{ __('Register') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
