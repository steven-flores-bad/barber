@props(['title', 'value', 'icon', 'variant' => 'white'])

@php
    $styles = $variant === 'amber' 
        ? 'bg-amber-500 text-slate-950' 
        : 'bg-white border border-slate-200 text-slate-900';
    
    $titleStyles = $variant === 'amber' ? 'opacity-70' : 'text-slate-400';
    $iconStyles = $variant === 'amber' ? 'opacity-30' : 'text-slate-300';
@endphp

<div {{ $attributes->merge(['class' => "$styles p-5 rounded-2xl shadow-sm flex items-center justify-between"]) }}>
    <div>
        <p class="text-xs font-bold uppercase tracking-wider {{ $titleStyles }}">{{ $title }}</p>
        <p class="text-3xl font-black mt-1">{{ $value }}</p>
    </div>
    <span class="text-3xl {{ $iconStyles }}">{{ $icon }}</span>
</div>