@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-navy-300 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
