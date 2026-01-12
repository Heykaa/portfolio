<section id="testimonials" class="py-40 bg-black overflow-hidden section-testimonials relative">
    <div class="container mx-auto px-6">
        <div class="mb-32 text-center">
            <h2 class="text-7xl md:text-9xl font-black tracking-tighter uppercase split-text">{{ $section->title }}</h2>
        </div>

        <div class="flex gap-20 testimonial-track overflow-hidden" id="testimonial-slider">
            @foreach($section->testimonials as $testimonial)
                <div class="testimonial-card min-w-full md:min-w-[50%] lg:min-w-[33%] p-10 border border-white/5 bg-white/[0.02] flex flex-col justify-between">
                    <div>
                        <div class="text-6xl font-serif text-white/10 mb-6">“</div>
                        <blockquote class="text-xl md:text-2xl font-light leading-relaxed mb-10 italic">
                            {{ $testimonial->quote }}
                        </blockquote>
                    </div>

                    <div class="flex items-center gap-6">
                        @if($testimonial->avatar_path)
                            <div class="w-16 h-16 rounded-full overflow-hidden border border-white/20">
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('uploads')->url($testimonial->avatar_path) }}"
                                    alt="{{ $testimonial->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-xs font-black tracking-tighter border border-white/20">
                                {{ substr($testimonial->name, 0, 2) }}
                            </div>
                        @endif

                        <div>
                            <div class="font-black tracking-tighter uppercase text-sm">{{ $testimonial->name }}</div>
                            <div class="text-[0.6rem] tracking-widest uppercase text-white/30 mt-1">{{ $testimonial->role }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
