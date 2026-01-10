import './bootstrap';
import { initLenis } from './animations/lenis';
import { initGsapAnimations } from './animations/gsap';
import { initSplitText } from './animations/splitText';

document.addEventListener('DOMContentLoaded', () => {
    // ✅ Run animations only on portfolio page
    const isPortfolio = document.body?.dataset?.page === 'portfolio';
    if (!isPortfolio) return;

    // Helper: safely run a function and never break the page
    const safeRun = (label, fn) => {
        try {
            fn();
        } catch (e) {
            console.warn(`[${label}] skipped due to error:`, e);
        }
    };

    // ✅ Ensure page never gets locked if something fails
    document.documentElement.classList.remove('is-loading');
    document.body.classList.remove('is-loading');

    // ✅ Kill forced hidden styles (common issue when GSAP fails)
    const forceVisible = () => {
        const selectors = ['#hero', '#smooth-content', '#smooth-wrapper'];
        selectors.forEach((sel) => {
            const el = document.querySelector(sel);
            if (el) {
                el.style.opacity = '';
                el.style.visibility = '';
                el.style.transform = '';
            }
        });

        // If any "data-animate" items were set hidden, restore them
        document.querySelectorAll('[data-animate]').forEach((el) => {
            el.style.opacity = '';
            el.style.visibility = '';
            el.style.transform = '';
        });
    };

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

        forceVisible();
    }

    // ✅ Final safety: if anything still hidden, force visible after 500ms
    setTimeout(() => {
        forceVisible();
    }, 500);

    console.log('Cinematic Portfolio Initialized');
});
