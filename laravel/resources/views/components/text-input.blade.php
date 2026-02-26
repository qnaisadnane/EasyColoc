@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-slate-900/50 border-white/10 focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/10 rounded-2xl shadow-inner text-slate-200 placeholder:text-slate-600 transition-all backdrop-blur-md']) }}>
