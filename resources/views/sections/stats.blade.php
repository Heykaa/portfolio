<section id="stats" class="py-40 bg-black border-y border-white/5 section-stats">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-20">
            @foreach($section->stats as $stat)
                <div class="stat-item flex flex-col items-center text-center">
                    <div class="text-8xl md:text-9xl font-black tracking-tighter mb-4 flex items-baseline">
                        <span class="counter" data-target="{{ $stat->value }}">0</span>
                        @if($stat->suffix)
                            <small class="text-4xl md:text-5xl opacity-30">{{ $stat->suffix }}</small>
                        @endif
                    </div>
                    <div class="text-white/40 tracking-[0.5em] uppercase text-xs">{{ $stat->label }}</div>
                    <div class="w-20 h-[1px] bg-white/10 mt-10"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>