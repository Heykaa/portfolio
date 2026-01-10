import './bootstrap';
import { initLenis } from './animations/lenis';
import { initGsapAnimations } from './animations/gsap';
import { initSplitText } from './animations/splitText';

document.addEventListener('DOMContentLoaded', () => {
    // Helper: safely run a function and never break the page
    const safeRun = (label, fn) => {
        try {
            fn();
        } catch (e) {
            console.warn(`[${label}] skipped بسبب error:`, e);
        }
    };

    // (Optional) Kalau ada class preload yang kunci content
    // Pastikan page tak terkunci walau animasi fail
    document.documentElement.classList.remove('is-loading');
    document.body.classList.remove('is-loading');

    // 1) Init smooth scroll (Lenis) - safe
    let lenis = null;
    safeRun('lenis', () => {
        lenis = initLenis();
    });

    // 2) Init split text - safe
    safeRun('splitText', () => {
        initSplitText();
    });

    // 3) Init GSAP - ONLY if needed elements exist
    const hasHero = !!document.querySelector('#hero');
    const hasHeroBg = !!document.querySelector('#hero-bg');
    const hasAppText = !!document.querySelector('.app-text');

    if (hasHero && hasHeroBg && hasAppText) {
        safeRun('gsap', () => {
            initGsapAnimations(lenis);
        });
    } else {
        console.warn('GSAP skipped: required elements not found', {
            hero: hasHero,
            heroBg: hasHeroBg,
            appText: hasAppText,
        });

        // Kalau animasi biasa set element opacity 0, pastikan dia nampak
        const hero = document.querySelector('#hero');
        if (hero) {
            hero.style.opacity = '';
            hero.style.visibility = '';
        }
    }

    console.log('Cinematic Portfolio Initialized');
});
