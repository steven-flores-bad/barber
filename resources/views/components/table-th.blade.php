@props(['align' => 'left'])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right'
    ][$align] ?? 'text-left';
@endphp

<th {{ $attributes->merge(['class' => "py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200 $alignmentClasses"]) }}>
    {{ $slot }}
</th>