<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-white">
    <div class="mb-4">
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md px-10 py-10 bg-navy-900 border border-navy-800 shadow-2xl overflow-hidden sm:rounded-3xl">
        {{ $slot }}
    </div>
</div>
