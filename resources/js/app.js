import './bootstrap';
import { initLenis } from './animations/lenis';
import { initGsapAnimations } from './animations/gsap';
import { initSplitText } from './animations/splitText';

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Smooth Scroll
    const lenis = initLenis();

    // 2. Initialize Text Splitting
    initSplitText();

    // 3. Initialize GSAP Animations
    initGsapAnimations(lenis);

    console.log('Cinematic Portfolio Initialized');
});
