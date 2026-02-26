<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-8 py-3 bg-indigo-600/90 hover:bg-indigo-500 border border-indigo-400/50 rounded-2xl font-bold text-sm text-white uppercase tracking-widest transition-all duration-300 active:scale-95 shadow-[0_0_20px_-5px_rgba(99,102,241,0.5)] hover:shadow-[0_0_25px_-2px_rgba(99,102,241,0.6)]']) }}>
    {{ $slot }}
</button>
