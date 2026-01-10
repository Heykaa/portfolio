import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export const initGsapAnimations = (lenis) => {
    // ===== Helpers =====
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const hasLenis = !!lenis && typeof lenis.on === 'function';

    const safe = (label, fn) => {
        try {
            fn();
        } catch (e) {
            console.warn(`[GSAP:${label}] skipped`, e);
        }
    };

    // Sync ScrollTrigger with Lenis (only if Lenis exists)
    if (hasLenis) {
        safe('lenis-sync', () => {
            lenis.on('scroll', ScrollTrigger.update);
        });
    }

    // ===== 1) Custom Cursor =====
    safe('cursor', () => {
        const cursor = $('#custom-cursor');
        if (!cursor) return;

        window.addEventListener('mousemove', (e) => {
            gsap.to(cursor, {
                x: e.clientX,
                y: e.clientY,
                duration: 0.1,
                ease: 'power2.out',
            });

            if (!cursor.classList.contains('active')) {
                cursor.style.display = 'block';
                cursor.classList.add('active');
            }
        });
    });

    // ===== 2) Hero Reveal =====
    safe('hero-reveal', () => {
        const heroBg = $('#hero-bg');
        const splitTextEls = $$('.split-text');

        // Kalau hero tak wujud, jangan buat timeline
        if (!heroBg || splitTextEls.length === 0) return;

        const heroTimeline = gsap.timeline();

        heroTimeline
            .from(splitTextEls, {
                y: 100,
                opacity: 0,
                duration: 1.5,
                stagger: 0.2,
                ease: 'power4.out',
                delay: 0.5,
            })
            .from(
                heroBg,
                {
                    scale: 1.5,
                    duration: 2.5,
                    ease: 'power2.out',
                },
                0
            );
    });

    // ===== 3) Reveal Cards on Scroll =====
    safe('reveal-cards', () => {
        const reveals = $$('.reveal-card');
        if (reveals.length === 0) return;

        reveals.forEach((reveal) => {
            ScrollTrigger.create({
                trigger: reveal,
                start: 'top 80%',
                onEnter: () => reveal.classList.add('is-inview'),
            });
        });
    });

    // ===== 4) Parallax Hero BG =====
    safe('hero-parallax', () => {
        const hero = $('#hero');
        const heroBg = $('#hero-bg');
        if (!hero || !heroBg) return;

        gsap.to(heroBg, {
            yPercent: 30,
            ease: 'none',
            scrollTrigger: {
                trigger: hero,
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    });

    // ===== 5) Counter Animations =====
    safe('counters', () => {
        const counters = $$('.counter');
        if (counters.length === 0) return;

        counters.forEach((counter) => {
            const raw = counter.getAttribute('data-target');
            const target = Number.parseInt(raw || '0', 10);
            if (!Number.isFinite(target) || target <= 0) return;

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
                            counter.innerText = Math.floor(count.value);
                        },
                    });
                },
            });
        });
    });

    // ===== 6) Stack Cube Tilt =====
    safe('stack-cube', () => {
        const cube = $('#stack-cube');
        const stack = $('#stack');
        if (!cube || !stack) return;

        ScrollTrigger.create({
            trigger: stack,
            start: 'top bottom',
            end: 'bottom top',
            onUpdate: (self) => {
                gsap.to(cube, {
                    rotateY: (self.progress - 0.5) * 60,
                    rotateX: (self.progress - 0.5) * -40,
                    duration: 0.5,
                    ease: 'power1.out',
                });
            },
        });
    });

    // ===== 7) Navbar Hide/Show =====
    safe('navbar', () => {
        const nav = $('#main-nav');
        if (!nav) return;

        // Kalau tiada Lenis, fallback scroll biasa
        let lastScroll = 0;

        const handle = (scroll) => {
            if (scroll > lastScroll && scroll > 100) {
                gsap.to(nav, { y: -100, duration: 0.5, ease: 'power2.out' });
            } else {
                gsap.to(nav, { y: 0, duration: 0.5, ease: 'power2.out' });
            }
            lastScroll = scroll;
        };

        if (hasLenis) {
            lenis.on('scroll', ({ scroll }) => handle(scroll));
        } else {
            window.addEventListener('scroll', () => handle(window.scrollY || 0), { passive: true });
        }
    });

    // ===== 8) Projects background + pinned scroller =====
    safe('projects-lines', () => initProjectsBackgroundLines());
    safe('pinned-projects', () => initPinnedProjects());
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

    if (!lines.length && !accentLines.length) return;

    // Horizontal lines moving UP at different speeds (parallax effect)
    lines.forEach((line, index) => {
        const speed = 0.8 + (index % 5) * 0.4;

        gsap.to(line, {
            y: `${-1 * 200 * speed}px`,
            x: `${(index % 2 === 0 ? 1 : -1) * 150}px`,
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1 + index * 0.1,
            },
        });
    });

    // Accent lines
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
                    ease: 'power2.out',
                });
                line.classList.add('is-visible');
            },
            onLeaveBack: () => {
                gsap.to(line, {
                    opacity: 0,
                    duration: 0.5,
                    ease: 'power2.out',
                });
                line.classList.remove('is-visible');
            },
        });

        const moveAmount = -400 - index * 200;

        gsap.to(line, {
            y: moveAmount,
            ease: 'none',
            scrollTrigger: {
                trigger: section,
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1.5,
            },
        });
    });

    // Container rotation
    gsap.to(bgLines, {
        rotateZ: -1.5,
        ease: 'none',
        scrollTrigger: {
            trigger: section,
            start: 'top bottom',
            end: 'bottom top',
            scrub: 3,
        },
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

    const getScrollDistance = () => {
        const dist = track.scrollWidth - viewport.clientWidth;
        return dist > 0 ? dist : 0;
    };

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
            },
        },
    });

    window.addEventListener('resize', () => ScrollTrigger.refresh());

    const images = track.querySelectorAll('img');
    images.forEach((img) => {
        if (img.complete) {
            ScrollTrigger.refresh();
        } else {
            img.addEventListener('load', () => ScrollTrigger.refresh());
        }
    });

    initCardSpotlight(Array.from(cards));
}

/**
 * Premium hover/focus spotlight effect for project cards
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
            const isStillInCards = cards.some((c) => c.contains(relatedTarget));
            if (!isStillInCards) deactivateSpotlight();
        });
    });
}
