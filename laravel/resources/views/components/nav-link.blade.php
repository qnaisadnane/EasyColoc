@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-xl bg-indigo-500/10 text-sm font-bold leading-5 text-indigo-400 border border-indigo-500/30 shadow-[0_0_15px_-5px_rgba(99,102,241,0.4)] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold leading-5 text-slate-400 border border-transparent hover:text-indigo-400 hover:bg-white/5 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
