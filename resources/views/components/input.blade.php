@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-white border-gray-300 text-navy-950 focus:border-gold-500 focus:ring-gold-500 rounded-xl shadow-sm placeholder-gray-400']) !!}>
