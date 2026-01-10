import Lenis from 'lenis';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export const initLenis = () => {
    // Kalau device tak support / user prefer reduce motion, boleh fallback
    const prefersReducedMotion =
        window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const lenis = new Lenis({
        duration: prefersReducedMotion ? 0.01 : 1.2,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        orientation: 'vertical',
        gestureOrientation: 'vertical',
        smoothWheel: !prefersReducedMotion,
        wheelMultiplier: 1,
        smoothTouch: false,
        touchMultiplier: 2,
        infinite: false,
    });

    function raf(time) {
        lenis.raf(time);

        // Sync ScrollTrigger properly
        try {
            ScrollTrigger.update();
        } catch (e) {
            // ignore if ScrollTrigger not ready
        }

        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    return lenis;
};
