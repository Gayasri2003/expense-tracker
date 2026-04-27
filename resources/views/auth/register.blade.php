<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="currency" value="{{ __('Preferred Currency Symbol') }}" />
                <select id="currency" name="currency" class="bg-white border-gray-300 text-navy-950 focus:border-gold-500 focus:ring-gold-500 rounded-xl shadow-sm mt-1 block w-full py-2.5 font-medium" required>
                    <option value="$" {{ old('currency') == '$' ? 'selected' : '' }}>$ - US Dollar (USD)</option>
                    <option value="€" {{ old('currency') == '€' ? 'selected' : '' }}>€ - Euro (EUR)</option>
                    <option value="£" {{ old('currency') == '£' ? 'selected' : '' }}>£ - British Pound (GBP)</option>
                    <option value="¥" {{ old('currency') == '¥' ? 'selected' : '' }}>¥ - Japanese Yen (JPY)</option>
                    <option value="₹" {{ old('currency') == '₹' ? 'selected' : '' }}>₹ - Indian Rupee (INR)</option>
                    <option value="Rs" {{ old('currency') == 'Rs' ? 'selected' : '' }}>Rs - Sri Lankan Rupee (LKR)</option>
                    <option value="A$" {{ old('currency') == 'A$' ? 'selected' : '' }}>A$ - Australian Dollar (AUD)</option>
                    <option value="C$" {{ old('currency') == 'C$' ? 'selected' : '' }}>C$ - Canadian Dollar (CAD)</option>
                    <option value="Fr" {{ old('currency') == 'Fr' ? 'selected' : '' }}>Fr - Swiss Franc (CHF)</option>
                </select>
                <x-input-error for="currency" class="mt-2" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required class="bg-navy-950 border-navy-800 text-gold-600 focus:ring-gold-500" />

                            <div class="ms-2 text-navy-400">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gold-500 hover:text-gold-400 rounded-md focus:outline-none transition">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gold-500 hover:text-gold-400 rounded-md focus:outline-none transition">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="mt-10 flex flex-col items-center space-y-6">
                <x-button>
                    {{ __('Register') }}
                </x-button>

                <a class="underline text-sm text-navy-400 hover:text-white rounded-md focus:outline-none transition" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
