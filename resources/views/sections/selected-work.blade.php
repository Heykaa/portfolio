<section id="work" class="py-40 bg-black">
    <div class="container mx-auto px-6">
        <div class="mb-32 flex flex-col md:flex-row justify-between items-end gap-10">
            <div>
                <h2 class="text-7xl md:text-8xl font-black tracking-tighter uppercase split-text">{{ $section->title }}
                </h2>
                <p class="text-green-400 tracking-widest uppercase mt-4 opacity-70">{{ $section->subtitle }}</p>
            </div>
            <div class="text-white/20 text-xs tracking-[0.5em] uppercase pb-2">Crafted with precision</div>
        </div>

        @php $cols = $section->layout['columns'] ?? 3; @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $cols }} gap-1">
            @foreach($section->works as $work)
                <div class="work-item relative aspect-square overflow-hidden group bg-white/5 reveal-card">
                    @if($work->image_path)
                        <img src="{{ asset('storage/' . $work->image_path) }}" alt="{{ $work->title }}"
                            class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-110 group-hover:scale-100 transition-all duration-1000">
                    @endif
                    <div
                        class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex flex-col justify-center items-center p-10 text-center">
                        <h4
                            class="text-2xl font-black tracking-tighter uppercase mb-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            {{ $work->title }}
                        </h4>
                        <div
                            class="flex gap-2 flex-wrap justify-center transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 delay-100">
                            @if($work->tags)
                                @foreach($work->tags as $tag)
                                    <span class="text-[0.6rem] tracking-widest uppercase opacity-60">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>