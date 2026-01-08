<section id="case-studies" class="py-40 bg-black min-h-screen">
    <div class="container mx-auto px-6">
        <div class="mb-32">
            <h2 class="text-7xl md:text-9xl font-black tracking-tighter uppercase split-text">{{ $section->title }}</h2>
            <p class="text-white/50 tracking-widest uppercase mt-4">{{ $section->subtitle }}</p>
        </div>

        <div class="flex flex-col gap-40">
            @foreach($section->caseStudies as $case)
                <div class="case-card flex flex-col md:flex-row gap-10 items-end group" data-scroll-speed="0.1">
                    <div class="w-full md:w-3/5 overflow-hidden aspect-video bg-white/5 reveal-card">
                        @if($case->image_path)
                            <img src="{{ asset('storage/' . $case->image_path) }}" alt="{{ $case->title }}"
                                class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-110 group-hover:scale-100 transition-all duration-1000">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/10 text-9xl font-black">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        @endif
                    </div>
                    <div class="w-full md:w-2/5 flex flex-col gap-6 pb-10">
                        <div class="flex gap-4">
                            @if($case->tags)
                                @foreach($case->tags as $tag)
                                    <span
                                        class="text-[0.6rem] tracking-widest uppercase border border-white/20 px-3 py-1">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                        <h3
                            class="text-4xl md:text-6xl font-black tracking-tighter uppercase group-hover:text-white transition-colors">
                            {{ $case->title }}</h3>
                        <p class="text-white/50 leading-relaxed font-light">{{ $case->caption }}</p>
                        <a href="{{ $case->url ?? '#' }}"
                            class="inline-flex items-center text-xs tracking-widest uppercase mt-4 gap-4 group/btn">
                            Explore Case
                            <span class="w-10 h-[1px] bg-white group-hover/btn:w-20 transition-all duration-500"></span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>