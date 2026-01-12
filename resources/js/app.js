import './bootstrap';
import { initLenis } from './animations/lenis';
import { initGsapAnimations } from './animations/gsap';
import { initSplitText } from './animations/splitText';

document.addEventListener('DOMContentLoaded', () => {
    // ✅ Only run cinematic animations on portfolio/public page
    const isPortfolioPage =
        document.querySelector('#hero') ||
        document.querySelector('[data-projects-section]') ||
        document.querySelector('body[data-portfolio-page]');

    if (!isPortfolioPage) return;

    const lenis = initLenis();
    initSplitText();
    initGsapAnimations(lenis);
});
