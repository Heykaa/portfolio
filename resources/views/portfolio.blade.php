    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="lenis lenis-smooth">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $settings->brand_name }} | {{ $settings->hero_title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100;300;400;500;700;900&display=swap"
            rel="stylesheet">

   @if($settings->favicon_path)
    <link rel="icon" href="{{ \Illuminate\Support\Facades\Storage::disk('uploads')->url($settings->favicon_path) }}">
@endif


        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --bg-dark: #0a0a0a;
                --text-light: #f5f5f5;
                --accent: #e0e0e0;
            }

            body {
                background-color: var(--bg-dark);
                color: var(--text-light);
                font-family: 'Outfit', sans-serif;
                overflow-x: hidden;
            }

            .lenis.lenis-smooth {
                height: auto;
            }

            .lenis.lenis-smooth [data-lenis-prevent] {
                overscroll-behavior: contain;
            }

            .lenis.lenis-stopped {
                overflow: hidden;
            }

            .lenis.lenis-scrolling iframe {
                pointer-events: none;
            }
        </style>
    </head>

    <body class="antialiased">
        <!-- Custom Cursor -->
        <div id="custom-cursor"
            class="fixed top-0 left-0 w-4 h-4 bg-white rounded-full pointer-events-none z-50 mix-blend-difference hidden md:block">
        </div>

        <!-- Navigation -->
        <nav class="fixed top-0 left-0 w-full z-40 p-6 flex justify-between items-center transition-all duration-500"
            id="main-nav">
            <a href="#" class="text-xl font-black tracking-tighter">{{ $settings->brand_name }}</a>
            <div class="flex gap-8 text-sm font-medium tracking-widest uppercase">
                <a href="#work" class="nav-link">Work</a>
                <a href="#about" class="nav-link">About</a>
                <a href="#contact" class="nav-link">Contact</a>
            </div>
        </nav>

        <main id="smooth-wrapper">
            <div id="smooth-content">
                @foreach($sections as $section)
                    @include('sections.' . str_replace('_', '-', $section->key), ['section' => $section])
                @endforeach
            </div>
        </main>

        <!-- Footer -->
        <footer class="p-20 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="text-4xl font-black tracking-tighter">{{ $settings->brand_name }}</div>
            <div class="flex gap-6">
                @if($settings->social_links)
                    @foreach($settings->social_links as $link)
                        <a href="{{ $link['url'] }}" target="_blank"
                            class="hover:text-white/50 transition-colors uppercase text-sm tracking-widest">{{ $link['label'] ?? 'Link' }}</a>
                    @endforeach
                @endif
            </div>
            <div class="text-white/30 text-xs">© {{ date('Y') }} {{ $settings->brand_name }}. ALL RIGHTS RESERVED.</div>
        </footer>
    </body>

    </html>
