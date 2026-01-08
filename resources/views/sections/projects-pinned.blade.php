{{-- Pinned Horizontal Projects Scroller --}}
<section id="projects-pinned" class="relative bg-black overflow-hidden" data-projects-section>

    {{-- Animated Background Lines --}}
    <div class="projects-bg-lines absolute inset-0 pointer-events-none overflow-hidden" data-projects-bg-lines>
        {{-- Multiple animated horizontal lines --}}
        @for($i = 0; $i < 15; $i++)
            <div class="projects-bg-line-h absolute bg-gradient-to-r from-transparent via-green-400/70 to-transparent"
                style="top: {{ ($i + 1) * 7 }}%; height: 1px; width: 200%; left: -50%;" data-line-index="{{ $i }}">
            </div>
        @endfor

        {{-- Bold floating green lines for more obvious effect --}}
        <div class="projects-accent-line-h absolute h-[2px] w-full bg-gradient-to-r from-transparent via-green-400/90 to-transparent"
            style="top: 25%;" data-accent-line="1"></div>
        <div class="projects-accent-line-h absolute h-[2px] w-full bg-gradient-to-r from-transparent via-green-400/80 to-transparent"
            style="top: 50%;" data-accent-line="2"></div>
        <div class="projects-accent-line-h absolute h-[2px] w-full bg-gradient-to-r from-transparent via-green-400/90 to-transparent"
            style="top: 75%;" data-accent-line="3"></div>
    </div>

    {{-- Section Header --}}
    <div class="container mx-auto px-6 pt-24 pb-10">
        <h2 class="text-7xl md:text-9xl font-black tracking-tighter uppercase split-text">
            {{ $section->title ?? 'PROJECTS' }}
        </h2>
        @if($section->subtitle)
            <p class="text-green-400 tracking-widest uppercase mt-4 opacity-70">{{ $section->subtitle }}</p>
        @endif
    </div>

    {{-- Pinned Viewport --}}
    <div class="projects-viewport h-screen relative" data-projects-viewport>
        {{-- Horizontal Track --}}
        <div class="projects-track flex items-center gap-8 px-6 h-full will-change-transform" data-projects-track>
            {{-- Leading Spacer --}}
            <div class="flex-shrink-0" style="width: 5vw;"></div>

            @foreach($section->works->where('enabled', true) as $work)
                <a href="{{ $work->url ?: '#' }}"
                    class="project-card group relative bg-white/5 rounded-sm overflow-hidden
                                                              transition-all duration-500 ease-out
                                                              focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-400/50 focus-visible:ring-offset-4 focus-visible:ring-offset-black"
                    style="width: clamp(350px, 35vw, 550px); aspect-ratio: 3/4; flex-shrink: 0;" data-project-card
                    aria-label="View project: {{ $work->title }}">

                    {{-- Project Image --}}
                    @if($work->image_path)
                        <img src="{{ asset('storage/' . $work->image_path) }}" alt="{{ $work->title }}"
                            class="project-card-image absolute inset-0 w-full h-full object-cover grayscale opacity-70
                                                                                                                            transition-all duration-700 ease-out" loading="lazy">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-white/10 text-[15rem] font-black">
                            {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    @endif

                    {{-- Overlay Gradient --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

                    {{-- Content --}}
                    <div class="absolute bottom-0 left-0 right-0 p-8 flex flex-col gap-4">
                        {{-- Tags --}}
                        @if($work->tags)
                            <div class="flex gap-3 flex-wrap">
                                @foreach($work->tags as $tag)
                                    <span
                                        class="text-[0.6rem] tracking-[0.3em] uppercase text-white/50 border border-white/20 px-3 py-1">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Title --}}
                        <h3 class="text-2xl md:text-3xl font-black tracking-tight uppercase leading-tight">
                            {{ $work->title }}
                        </h3>

                        {{-- Caption --}}
                        @if($work->caption)
                            <p class="text-white/50 text-sm font-light leading-relaxed line-clamp-2">
                                {{ $work->caption }}
                            </p>
                        @endif

                        {{-- Arrow Indicator --}}
                        <div
                            class="flex items-center gap-4 mt-2 opacity-0 group-hover:opacity-100 group-focus-visible:opacity-100 transition-opacity duration-300">
                            <span class="text-xs tracking-[0.3em] uppercase text-green-400">Explore</span>
                            <span class="w-8 h-[1px] bg-green-400"></span>
                        </div>
                    </div>
                </a>
            @endforeach

            {{-- Trailing Spacer --}}
            <div class="flex-shrink-0" style="width: 5vw;"></div>
        </div>
    </div>

    {{-- Progress Indicator --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-10">
        <div class="flex items-center gap-4 text-green-400/30">
            <span class="text-[0.6rem] tracking-[0.5em] uppercase">Scroll</span>
            <div class="w-20 h-[1px] bg-green-400/10 relative overflow-hidden">
                <div class="projects-progress absolute inset-y-0 left-0 bg-green-400 w-0"></div>
            </div>
        </div>
    </div>
</section>