<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-8 py-3 bg-gold-700 border border-transparent rounded-xl font-extrabold text-sm text-white uppercase tracking-widest shadow-md hover:bg-gold-800 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:ring-offset-2 active:bg-gold-900 disabled:opacity-50 transition ease-in-out duration-150 w-full']) }}>
    {{ $slot }}
</button>
