<section id="hero"
    class="h-screen w-full flex flex-col justify-center items-center text-center p-6 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute inset-0 z-0 bg-gradient-to-b from-black/0 to-black"></div>
    @if($settings->hero_video_path)
        <video autoplay muted loop playsinline
            class="absolute inset-0 z-[-1] w-full h-full object-cover opacity-40 scale-110 transition-all duration-1000"
            id="hero-bg">
            <source src="{{ asset('storage/' . $settings->hero_video_path) }}" type="video/mp4">
        </video>
    @elseif($settings->hero_image_path)
        <div class="absolute inset-0 z-[-1] scale-110 opacity-40 bg-cover bg-center transition-all duration-1000"
            id="hero-bg" style="background-image: url('{{ asset('storage/' . $settings->hero_image_path) }}')"></div>
    @endif

    <div class="z-10 container mx-auto" id="hero-reveal">
        <h1 class="text-[clamp(3rem,15vw,12rem)] font-black tracking-tighter leading-[0.85] uppercase split-text"
            id="hero-title">
            {{ $settings->hero_title ?? 'CRAFTING DIGITAL' }}
        </h1>
        <h2 class="text-[clamp(1rem,4vw,3rem)] font-light tracking-[0.4em] mt-8 uppercase split-text opacity-70 text-white"
            id="hero-subtitle">
            {{ $settings->hero_subtitle ?? 'YOUR JERSEY SPECIALIST' }}
        </h2>

        <div class="mt-20 overflow-hidden">
            <a href="{{ $settings->hero_cta_url ?? '#work' }}"
                class="inline-block px-12 py-5 border border-white text-xs tracking-[0.5em] font-medium hover:bg-white hover:text-black transition-all duration-500 transform hover:scale-110"
                id="hero-cta">
                {{ $settings->hero_cta_text ?? 'VIEW PROJECTS' }}
            </a>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-4 opacity-70"
        id="hero-scroll">
        <span class="text-[0.6rem] tracking-[0.5em] uppercase text-green-400">Scroll</span>
        <div class="w-[1px] h-20 bg-gradient-to-b from-green-400 to-transparent origin-top" id="scroll-line"></div>
    </div>
</section>