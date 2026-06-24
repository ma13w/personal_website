<!-- THEME SYSTEM JS -->
    <script>
        const THEME_KEY = 'mattew-theme';

        function applyTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        function getStoredTheme() {
            var stored = localStorage.getItem(THEME_KEY);
            if (!stored) {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            return stored;
        }

        function toggleTheme() {
            const current = getStoredTheme();
            const next = current === 'light' ? 'dark' : 'light';
            localStorage.setItem(THEME_KEY, next);
            applyTheme(next);
            if (window.gsap) {
                gsap.to('.theme-toggle-thumb', {
                    x: next === 'dark' ? 18 : 0,
                    duration: 0.35,
                    ease: 'back.out(1.7)'
                });
            }
        }

        applyTheme(getStoredTheme());

        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('themeToggle');
            if (btn) btn.addEventListener('click', toggleTheme);
        });
    </script>

    <!-- GSAP CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script>
        /* ============================================
           REGISTER PLUGINS
           ============================================ */
        gsap.registerPlugin(ScrollTrigger);

        /* ============================================
           CUSTOM CURSOR
           ============================================ */
        (() => {
            const cursor = document.getElementById('cursor');
            if (!cursor) { console.warn('[CURSOR] #cursor not found - aborting'); return; }

            const isTouch = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            if (isTouch) {
                cursor.style.display = 'none';
                return;
            }
            

            let mouseX = -100, mouseY = -100;
            let cursorX = -100, cursorY = -100;
            let moveCount = 0;

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                if (moveCount < 5) {
                } else {
                    moveCount++;
                }
            });

            function updateCursor() {
                const ease = 0.15;
                cursorX += (mouseX - cursorX) * ease;
                cursorY += (mouseY - cursorY) * ease;
                cursor.style.left = cursorX + 'px';
                cursor.style.top = cursorY + 'px';
                requestAnimationFrame(updateCursor);
            }
            updateCursor();

            const hoverEls = document.querySelectorAll('<?= $cursorHoverSelector ?? 'a, button, [data-hover], .edu-question' ?>');
            hoverEls.forEach(el => {
                el.addEventListener('mouseenter', () => cursor.classList.add('hover'));
                el.addEventListener('mouseleave', () => cursor.classList.remove('hover'));
            });

            document.addEventListener('mouseleave', () => { cursor.style.opacity = '0'; });
            document.addEventListener('mouseenter', () => { cursor.style.opacity = '1'; });
        })();

        /* ============================================
           NAV REVEAL
           ============================================ */
        gsap.from('.navbar', {
            y: -30, opacity: 0,
            duration: 0.8,
            ease: 'power2.out',
            delay: 0.1,
            clearProps: 'opacity,y'
        });

        /* ============================================
           HAMBURGER MENU - Open / Close
           ============================================ */
        (() => {
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const navOverlay = document.getElementById('navOverlay');
            const navLinks = document.querySelectorAll('.nav-link');
            const lines = document.querySelectorAll('.hamburger-line');
            let isOpen = false;

            const openMenu = () => {
                isOpen = true;
                document.body.style.overflow = 'hidden';

                // Overlay scende dall'alto
                gsap.to(navOverlay, {
                    clipPath: 'inset(0 0 0% 0)',
                    duration: 0.6,
                    ease: 'power4.inOut',
                    onStart: () => {
                        navOverlay.style.visibility = 'visible';
                        navOverlay.classList.add('is-open');
                    }
                });

                // Link appaiono in stagger
                gsap.to(navLinks, {
                    opacity: 1,
                    y: 0,
                    duration: 0.5,
                    stagger: 0.08,
                    ease: 'power3.out',
                    delay: 0.3
                });

                // Hamburger → X
                gsap.to(lines[0], { rotate: 45, y: 3.75, duration: 0.4, ease: 'power3.inOut' });
                gsap.to(lines[1], { rotate: -45, y: -3.75, duration: 0.4, ease: 'power3.inOut' });
            };

            const closeMenu = () => {
                isOpen = false;
                document.body.style.overflow = '';

                // Link scompaiono
                gsap.to(navLinks, {
                    opacity: 0,
                    y: 20,
                    duration: 0.3,
                    stagger: 0.04,
                    ease: 'power2.in'
                });

                // Overlay sale verso l'alto
                gsap.to(navOverlay, {
                    clipPath: 'inset(0 0 100% 0)',
                    duration: 0.5,
                    ease: 'power4.inOut',
                    delay: 0.15,
                    onComplete: () => {
                        navOverlay.classList.remove('is-open');
                        navOverlay.style.visibility = 'hidden';
                    }
                });

                // X → Hamburger
                gsap.to(lines[0], { rotate: 0, y: 0, duration: 0.4, ease: 'power3.inOut' });
                gsap.to(lines[1], { rotate: 0, y: 0, duration: 0.4, ease: 'power3.inOut' });
            };

            hamburgerBtn.addEventListener('click', () => {
                isOpen ? closeMenu() : openMenu();
            });

            // Chiudi menu al click su un link
            navLinks.forEach(link => {
                link.addEventListener('click', closeMenu);
            });
        })();

        /* ============================================
           FOOTER - Scroll Reveal
           ============================================ */
        gsap.from('.footer-brand', {
            y: 30, opacity: 0, duration: 0.8, ease: 'power2.out',
            scrollTrigger: { trigger: '.footer', start: 'top 88%' }
        });

        gsap.from('.footer-right', {
            y: 20, opacity: 0, duration: 0.8, ease: 'power2.out', delay: 0.1,
            scrollTrigger: { trigger: '.footer', start: 'top 88%' }
        });

        gsap.from('.footer-bottom', {
            y: 15, opacity: 0, duration: 0.7, ease: 'power2.out', delay: 0.2,
            scrollTrigger: { trigger: '.footer-bottom', start: 'top 95%' }
        });
