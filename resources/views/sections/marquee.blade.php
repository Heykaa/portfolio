<section id="marquee" class="py-20 border-y border-white/5 bg-black overflow-hidden whitespace-nowrap">
    <div class="flex marquee-inner" id="marquee-text">
@php 
            $text = $section->data['text'] ?? 'AVAILABLE FOR FREELANCE • DESIGN DRIVEN • FULL-STACK DEVELOPMENT •';
            $parts = array_filter(explode('•', $text));
        @endphp
        @for($i = 0; $i < 6; $i++)
            @foreach($parts as $index => $part)
                @php $isGreen = (($i * count($parts) + $index) % 2 === 0); @endphp
                <span
                    class="text-[clamp(2rem,6vw,5rem)] font-black tracking-tighter uppercase px-6 text-transparent"
                    style="-webkit-text-stroke: 1px {{ $isGreen ? 'rgba(0, 255, 100, 0.5)' : 'rgba(255, 255, 255, 0.4)' }}">
                    {{ trim($part) }}
                </span>
                <span class="text-green-400/20 text-4xl px-4 select-none">•</span>
            @endforeach
        @endfor
    </div>
</section>