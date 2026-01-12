import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export const initGsapAnimations = (lenis) => {
    // --- Safety: Lenis optional ---
    if (lenis?.on) {
        lenis.on('scroll', ScrollTrigger.update);
    }

    // 1) Custom Cursor
    const cursor = document.getElementById('custom-cursor');
    if (cursor) {
        window.addEventListener('mousemove', (e) => {
            gsap.to(cursor, {
                x: e.clientX,
                y: e.clientY,
                duration: 0.1,
                ease: 'power2.out'
            });

            if (!cursor.classList.contains('active')) {
                cursor.style.display = 'block';
                cursor.classList.add('active');
            }
        });
    }

    // 2) Hero Reveal (guarded)
    const splitTargets = document.querySelectorAll('.split-text');
    const heroBg = document.querySelector('#hero-bg');

    const heroTimeline = gsap.timeline();

    if (splitTargets.length) {
        heroTimeline.from(splitTargets, {
            y: 100,
            opacity: 0,
            duration: 1.5,
            stagger: 0.2,
            ease: 'power4.out',
            delay: 0.5
        }, 0);
    }

    if (heroBg) {
        heroTimeline.from(heroBg, {
            scale: 1.5,
            duration: 2.5,
            ease: 'power2.out'
        }, 0);

        // 4) Parallax hero background (only if heroBg exists)
        gsap.to(heroBg, {
            yPercent: 30,
            ease: 'none',
            scrollTrigger: {
                trigger: '#hero',
                start: 'top top',
                end: 'bottom top',
                scrub: true
            }
        });
    }

    // 3) Reveal Cards on Scroll
    const reveals = document.querySelectorAll('.reveal-card');
    reveals.forEach((reveal) => {
        ScrollTrigger.create({
            trigger: reveal,
            start: 'top 80%',
            onEnter: () => reveal.classList.add('is-inview')
        });
    });

    // 5) Counter Animations (guard data-target)
    const counters = document.querySelectorAll('.counter');
    counters.forEach((counter) => {
        const raw = counter.getAttribute('data-target');
        const target = Number.parseInt(raw ?? '', 10);
        if (!Number.isFinite(target)) return;

        ScrollTrigger.create({
            trigger: counter,
            start: 'top 90%',
            once: true,
            onEnter: () => {
                const count = { value: 0 };
                gsap.to(count, {
                    value: target,
                    duration: 2,
                    ease: 'power2.out',
                    onUpdate: () => {
                        counter.innerText = String(Math.floor(count.value));
                    }
                });
            }
        });
    });

    // 6) Stack Cube Tilt
    const cube = document.getElementById('stack-cube');
    const stackSection = document.getElementById('stack');
    if (cube && stackSection) {
        ScrollTrigger.create({
            trigger: stackSection,
            start: 'top bottom',
            end: 'bottom top',
            onUpdate: (self) => {
                gsap.to(cube, {
                    rotateY: (self.progress - 0.5) * 60,
                    rotateX: (self.progress - 0.5) * -40,
                    duration: 0.5,
                    ease: 'power1.out'
                });
            }
        });
    }

    // 7) Navbar Hide/Show (guard nav + lenis)
    const nav = document.getElementById('main-nav');
    if (nav && lenis?.on) {
        let lastScroll = 0;

        lenis.on('scroll', ({ scroll }) => {
            if (scroll > lastScroll && scroll > 100) {
                gsap.to(nav, { y: -100, duration: 0.5, ease: 'power2.out' });
            } else {
                gsap.to(nav, { y: 0, duration: 0.5, ease: 'power2.out' });
            }
            lastScroll = scroll;
        });
    }

    // 8) Pinned Horizontal Projects Scroller with Background Animation
    initProjectsBackgroundLines();
    initPinnedProjects();
};

/**
 * Animated background lines for projects section
 */
function initProjectsBackgroundLines() {
    const section = document.querySelector('[data-projects-section]');
    const bgLines = document.querySelector('[data-projects-bg-lines]');

    if (!section || !bgLines) return;

    const lines = bgLines.querySelectorAll('.projects-bg-line-h');
    const accentLines = bgLines.querySelectorAll('.projects-accent-line-h');

    lines.forEach((line, index) => {
        const speed = 0.8 + (index % 5) * 0.4;
        const direction = -1;

        gsap.to(line, {
            y: `${direction * 200 * speed}px`,
            x: `${(index % 2 === 0 ? 1 : -1) * 150}px`,
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1 + (index * 0.1),
            }
        });
    });

    accentLines.forEach((line, index) => {
        const delay = index * 0.2;

        ScrollTrigger.create({
            trigger: section,
            start: 'top 80%',
            onEnter: () => {
                gsap.to(line, {
                    opacity: 1,
                    duration: 0.8,
                    delay,
                    ease: 'power2.out'
                });
                line.classList.add('is-visible');
            },
            onLeaveBack: () => {
                gsap.to(line, {
                    opacity: 0,
                    duration: 0.5,
                    ease: 'power2.out'
                });
                line.classList.remove('is-visible');
            }
        });

        const moveAmount = -400 - (index * 200);
        gsap.to(line, {
            y: moveAmount,
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5
            }
        });
    });

    gsap.to(bgLines, {
        rotateZ: -1.5,
        ease: 'none',
        scrollTrigger: {
            trigger: section,
            start: 'top bottom',
            end: 'bottom top',
            scrub: 3
        }
    });
}

/**
 * Pinned horizontal scroll for projects section
 */
function initPinnedProjects() {
    const section = document.querySelector('[data-projects-section]');
    const viewport = document.querySelector('[data-projects-viewport]');
    const track = document.querySelector('[data-projects-track]');
    const cards = document.querySelectorAll('[data-project-card]');
    const progressBar = document.querySelector('.projects-progress');

    if (!section || !viewport || !track || cards.length === 0) return;

    const getScrollDistance = () => Math.max(0, track.scrollWidth - viewport.clientWidth);

    gsap.to(track, {
        x: () => -getScrollDistance(),
        ease: 'none',
        scrollTrigger: {
            trigger: viewport,
            start: 'top top',
            end: () => '+=' + getScrollDistance(),
            pin: true,
            anticipatePin: 1,
            scrub: 0.5,
            invalidateOnRefresh: true,
            onUpdate: (self) => {
                if (progressBar) {
                    gsap.set(progressBar, { width: `${self.progress * 100}%` });
                }
            }
        }
    });

    window.addEventListener('resize', () => ScrollTrigger.refresh());

    const images = track.querySelectorAll('img');
    images.forEach((img) => {
        if (img.complete) {
            ScrollTrigger.refresh();
        } else {
            img.addEventListener('load', () => ScrollTrigger.refresh(), { once: true });
        }
    });

    initCardSpotlight(cards);
}

/**
 * Spotlight Hover/Focus Behavior
 */
function initCardSpotlight(cards) {
    if (!cards || cards.length === 0) return;

    const activateSpotlight = (targetCard) => {
        cards.forEach((card) => {
            if (card === targetCard) {
                card.classList.add('is-spotlight');
                card.classList.remove('is-dimmed');
            } else {
                card.classList.add('is-dimmed');
                card.classList.remove('is-spotlight');
            }
        });
    };

    const deactivateSpotlight = () => {
        cards.forEach((card) => {
            card.classList.remove('is-spotlight', 'is-dimmed');
        });
    };

    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => activateSpotlight(card));
        card.addEventListener('mouseleave', deactivateSpotlight);

        card.addEventListener('focusin', () => activateSpotlight(card));
        card.addEventListener('focusout', (e) => {
            const relatedTarget = e.relatedTarget;
            const isStillInCards = Array.from(cards).some((c) => c.contains(relatedTarget));
            if (!isStillInCards) {
                deactivateSpotlight();
            }
        });
    });
}
