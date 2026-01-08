<section id="stack" class="py-40 bg-black overflow-hidden relative min-h-screen flex items-center">
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row gap-20 items-center">
            <div class="w-full md:w-1/2">
                <h2 class="text-7xl md:text-8xl font-black tracking-tighter uppercase split-text mb-10">{{ $section->title }}</h2>
                <div class="space-y-6">
                    @php 
                        $stack = ['LARAVEL', 'FILAMENT', 'GSAP', 'THREE.JS', 'TAILWIND', 'VITE'];
                    @endphp
                    @foreach($stack as $item)
                        <div class="stack-row group cursor-default">
                            <div class="text-4xl md:text-6xl font-black tracking-tighter opacity-20 group-hover:opacity-100 transition-all duration-500 transform group-hover:translate-x-4">
                                {{ $item }}
                            </div>
                            <div class="w-0 group-hover:w-full h-[1px] bg-white/20 mt-2 transition-all duration-500"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="w-full md:w-1/2 h-[500px] flex items-center justify-center relative perspective-2000">
                <div class="w-64 h-64 bg-white/5 border border-white/10 flex items-center justify-center transform rotate-x-12 rotate-y-12 rotate-z-3 hover:translate-z-20 transition-all duration-1000 shadow-[0_0_50px_rgba(255,255,255,0.05)]" id="stack-cube">
                    <span class="text-8xl font-black tracking-tighter opacity-20">PHP</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .perspective-2000 { perspective: 2000px; }
    .rotate-x-12 { transform: rotateX(12deg); }
    .rotate-y-12 { transform: rotateY(12deg); }
    .rotate-z-12 { transform: rotateZ(12deg); }
</style>
