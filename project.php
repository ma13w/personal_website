<?php
require_once __DIR__ . '/components/helpers.php';

$slug = $_GET['slug'] ?? '';

// ── 1. BLOCKLIST (caratteri pericolosi espliciti) ──────────────────
if (is_array($slug) || !is_string($slug)
    || strpos($slug, '..') !== false
    || strpos($slug, '/')  !== false
    || strpos($slug, '\\') !== false
) {
    http_response_code(400);
    echo '<!DOCTYPE html><html><head><title>418 - I\'m a teapot</title></head><body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f0f0ec;"><h1>Go here if you wanna play: <a href="https://ctf-training.profsamy.it/challenges">https://ctf-training.profsamy.it</a></h1></body></html>';
    exit;
}

// ── 2. ALLOWLIST (solo slug validi: a-z, 0-9, trattino) ───────────
if (!preg_match('/^[a-z0-9\-]{1,80}$/', $slug)) {
    http_response_code(400);
    echo '<!DOCTYPE html><html><head><title>418 - I\'m a teapot</title></head><body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f0f0ec;"><h1>Go here if you wanna play: <a href="https://ctf-training.profsamy.it/challenges">https://ctf-training.profsamy.it</a></h1></body></html>';
    exit;
}

// ── 3. Solo dopo la validazione, cerchiamo nel JSON ───────────────
$data    = loadProjectsData();
$project = null;
foreach ($data['projects'] as $p) {
    if ($p['slug'] === $slug) {
        $project = $p;
        break;
    }
}

if (!$project) {
    http_response_code(404);
    echo '<!DOCTYPE html><html><head><title>404 - Not Found</title></head><body style="font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f0f0ec;"><h1>Project not found</h1></body></html>';
    exit;
}

$title       = e($project['title']);
$badge       = e($project['badge']);
$year        = e($project['year']);
$topic        = e($project['topic']);
$services     = e(implode(', ', $project['services']));
$collaboration = !empty($project['collaboration']) ? e($project['collaboration']) : '-';
$collaboration = htmlspecialchars_decode($collaboration);
$heroDesc    = e($project['hero_description'] ?? '');
$hasImage    = !empty($project['visual']['image']);
$hasGradient = !empty($project['visual']['gradient']);
$visualGrad  = $hasGradient ? e($project['visual']['gradient']) : '';
$visualImages = [];
if ($hasImage) {
    $img = $project['visual']['image'];
    $visualImages = is_array($img) ? $img : [$img];
}
$gallery     = $project['gallery'];
$bodyParagraphs = $project['body_paragraphs'];
$nextProject = $project['next_project'];
// $nextHref    = $nextProject['slug'] ? 'project/' . urlencode($nextProject['slug']) : '#';
$nextHref = $nextProject['slug'] ? 'project.php?slug=' . urlencode($nextProject['slug']) : '#';
$nextTitle   = e($nextProject['title']);

$isHome = false;
$cursorHoverSelector = 'a, button, [data-hover], .next-title-link, .carousel-btn';

$pageTitle = $title . ' - Project';
$pageDesc  = $heroDesc
    ? strip_tags($heroDesc)
    : 'Explore the ' . $title . ' project by Matteo Cali - ' . $services . '.';
$pageImage = !empty($visualImages[0]) ? 'https://calimatteo.it' . $visualImages[0] : null;
$pageUrl   = 'https://calimatteo.it/project.php?id=' . urlencode($projectId ?? '');

require __DIR__ . '/components/head.php';
?>


        .cursor {
            mix-blend-mode: normal;
            border-color: var(--color-text-muted);
            background-color: transparent;
        }

        .cursor.hover {
            border-color: var(--color-text-primary);
            background: rgba(128, 128, 128, 0.08);
        }
        .theme-toggle {
            mix-blend-mode: normal;
            isolation: isolate;
        }

        /* ============================================
           PROJECT HERO - Two-column layout
           ============================================ */
        .project-hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            padding: 140px 60px 80px;
            max-width: 1400px;
            margin: 0 auto;
            align-items: start;
        }

        /* --- Left column: metadata --- */
        .project-meta {
            display: flex;
            flex-direction: column;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 20px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .meta-item:first-child {
            padding-top: 0;
        }

        .meta-label {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .meta-value {
            font-size: 1rem;
            color: var(--color-text-primary);
            font-weight: 500;
        }

        /* --- Right column: title + badge + description --- */
        .project-hero-content {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .project-title-wrap {
            overflow: hidden;
        }

        .project-title {
            font-size: clamp(3.5rem, 8vw, 8rem);
            font-weight: 800;
            line-height: 0.9;
            letter-spacing: -0.03em;
            color: var(--color-text-primary);
        }

        .project-badge {
            display: inline-block;
            background: var(--color-tag-bg);
            color: var(--color-tag-text);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 999px;
            letter-spacing: 0.02em;
            width: fit-content;
        }

        .project-description {
            font-size: clamp(1rem, 1.4vw, 1.15rem);
            line-height: 1.7;
            color: var(--color-text-secondary);
            max-width: 500px;
        }

        /* ============================================
           PROJECT VISUAL - Full-width image/hero
           ============================================ */
        .project-visual-wrap {
            margin: 0 60px;
            overflow: hidden;
            border-radius: 16px;
        }

        .project-visual {
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 16px;
            position: relative;
            overflow: hidden;
        }

        .project-visual img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* ── Image Carousel ── */
        .visual-carousel {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 16px;
            overflow: hidden;
        }

        .carousel-track {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .carousel-slide {
            min-width: 100%;
            height: 100%;
            flex-shrink: 0;
        }

        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .carousel-controls {
            position: absolute;
            bottom: clamp(1rem, 2vw, 1.5rem);
            right: clamp(1rem, 2vw, 1.5rem);
            display: flex;
            gap: 8px;
            z-index: 5;
        }

        .carousel-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s, border-color 0.3s;
        }

        .carousel-btn:hover {
            background: rgba(0, 0, 0, 0.5);
            border-color: rgba(255, 255, 255, 0.8);
        }

        .carousel-btn svg {
            width: 18px;
            height: 18px;
        }

        .carousel-dots {
            position: absolute;
            bottom: clamp(1rem, 2vw, 1.5rem);
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 5;
        }

        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: background 0.3s, transform 0.3s;
            cursor: pointer;
        }

        .carousel-dot.active {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(1.2);
        }

        /* SVG decorative elements (gradient visuals) */
        .visual-deco {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .visual-deco svg {
            width: 100%;
            height: 100%;
        }

        /* ============================================
           PROJECT BODY - Description section
           ============================================ */
        .project-body {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 60px;
            padding: 80px 60px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .body-label {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding-top: 6px;
        }

        .body-text {
            font-size: clamp(1.1rem, 1.8vw, 1.4rem);
            line-height: 1.7;
            color: var(--color-text-secondary);
            font-weight: 400;
        }

        .body-text p+p {
            margin-top: 1.5em;
        }

        /* ============================================
           IMAGE GALLERY - Two images side by side
           ============================================ */
        .project-gallery {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 0 60px 80px;
        }

        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            will-change: transform;
        }

        .gallery-item.landscape {
            grid-column: span 2;
        }

        .gallery-item-inner {
            width: 100%;
            height: 100%;
            border-radius: 12px;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .gallery-item:hover .gallery-item-inner {
            transform: scale(1.02);
        }

        /* ============================================
           NEXT PROJECT - "Next project" section
           ============================================ */
        .next-project {
            padding: 80px 60px 120px;
            border-top: 1px solid var(--color-border);
            max-width: 1400px;
            margin: 0 auto;
        }

        .next-label {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 24px;
        }

        .next-title-link {
            display: flex;
            align-items: center;
            gap: 24px;
            cursor: none;
            position: relative;
        }

        .next-title {
            font-size: clamp(3rem, 7vw, 7rem);
            font-weight: 800;
            color: var(--color-text-primary);
            display: block;
            position: relative;
            line-height: 1;
            letter-spacing: -0.03em;
            will-change: transform;
        }

        .next-title::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0%;
            height: 3px;
            background: var(--color-text-primary);
            transition: width 0.4s ease;
        }

        .next-title-link:hover .next-title::after {
            width: 100%;
        }

        .next-arrow {
            font-size: clamp(2rem, 4vw, 3.5rem);
            color: var(--color-text-primary);
            font-weight: 300;
            line-height: 1;
            transition: transform 0.4s ease;
        }

        .next-title-link:hover .next-arrow {
            transform: translate(6px, -6px);
        }

        /* Next project custom cursor */
        .next-cursor {
            position: absolute;
            width: 80px;
            height: 80px;
            background: var(--color-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            scale: 0.5;
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 10;
            top: 0;
            left: 0;
            will-change: transform, opacity;
        }

        .next-cursor svg {
            width: 22px;
            height: 22px;
        }

        @media (max-width: 768px) {
            .project-hero {
                grid-template-columns: 1fr;
                gap: 32px;
                padding: 70px 20px 40px;
            }

            .project-meta {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0;
            }

            .meta-label {
                font-size: 0.65rem;
            }

            .meta-value {
                font-size: 0.88rem;
            }

            .project-title {
                font-size: clamp(2.8rem, 13vw, 5rem);
                line-height: 0.92;
            }

            .project-badge {
                font-size: 0.72rem;
                padding: 5px 12px;
            }

            .project-description {
                font-size: 0.95rem;
                line-height: 1.65;
                max-width: 100%;
            }

            .project-visual-wrap {
                margin: 0 20px;
                border-radius: 12px;
            }

            .project-visual {
                aspect-ratio: 4 / 3;
                border-radius: 12px;
            }

            .visual-carousel {
                aspect-ratio: 4 / 3;
                border-radius: 12px;
            }

            .carousel-btn {
                width: 34px;
                height: 34px;
            }

            .carousel-btn svg {
                width: 14px;
                height: 14px;
            }

            .project-body {
                grid-template-columns: 1fr;
                gap: 16px;
                padding: 48px 20px;
            }

            .body-label {
                font-size: 0.7rem;
            }

            .body-text {
                font-size: 1rem;
                line-height: 1.7;
            }

            .project-gallery {
                grid-template-columns: 1fr;
                margin: 0 20px 48px;
                gap: 12px;
            }

            .gallery-item.landscape {
                grid-column: span 1;
            }

            .next-project {
                padding: 48px 20px 64px;
            }

            .next-label {
                font-size: 0.7rem;
                margin-bottom: 16px;
            }

            .next-title {
                font-size: clamp(2.2rem, 11vw, 3.8rem);
            }

            .next-arrow {
                font-size: clamp(1.5rem, 7vw, 2.5rem);
            }

            .next-cursor {
                display: none;
            }

            .next-title-link {
                cursor: auto;
                gap: 14px;
            }
        }
    </style>
</head>

<body>

<?php require __DIR__ . '/components/nav.php'; ?>

    <!-- ============================================
         PROJECT HERO
         ============================================ -->
    <section class="project-hero">
        <!-- Left column: metadata -->
        <div class="project-meta">
            <div class="meta-item">
                <span class="meta-label">Topic</span>
                <span class="meta-value"><?= $topic ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Services</span>
                <span class="meta-value"><?= $services ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Year</span>
                <span class="meta-value"><?= $year ?></span>
            </div>
            <div class="meta-item">
                <span class="meta-label">Collaboration</span>
                <span class="meta-value"><?= $collaboration ?></span>
            </div>
        </div>

        <!-- Right column: title + badge + description -->
        <div class="project-hero-content">
            <div class="project-title-wrap">
                <h1 class="project-title"><?= $title ?></h1>
            </div>
            <span class="project-badge"><?= $badge ?></span>
            <?php if ($heroDesc): ?>
            <p class="project-description"><?= $heroDesc ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- ============================================
         PROJECT VISUAL - Hero image / carousel / gradient
         ============================================ -->
    <div class="project-visual-wrap">
        <?php if ($hasImage && count($visualImages) > 1): ?>
        <!-- Multiple images → Carousel -->
        <div class="visual-carousel" id="visualCarousel">
            <div class="carousel-track" id="carouselTrack">
                <?php foreach ($visualImages as $i => $img): ?>
                <div class="carousel-slide">
                    <img src="<?= e($img) ?>" alt="<?= $title ?> - <?= $i + 1 ?>" loading="<?= $i === 0 ? 'eager' : 'lazy' ?>" />
                </div>
                <?php endforeach; ?>
            </div>
            <div class="carousel-dots" id="carouselDots">
                <?php foreach ($visualImages as $i => $img): ?>
                <span class="carousel-dot<?= $i === 0 ? ' active' : '' ?>" data-index="<?= $i ?>"></span>
                <?php endforeach; ?>
            </div>
            <div class="carousel-controls">
                <button class="carousel-btn" id="carouselPrev" aria-label="Previous">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 18l-6-6 6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="carousel-btn" id="carouselNext" aria-label="Next">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 18l6-6-6-6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>
        <?php elseif ($hasImage && count($visualImages) === 1): ?>
        <!-- Single image -->
        <div class="project-visual">
            <img src="<?= e($visualImages[0]) ?>" alt="<?= $title ?>" />
        </div>
        <?php elseif ($hasGradient): ?>
        <!-- Gradient background -->
        <div class="project-visual" style="background: <?= $visualGrad ?>;">
            <div class="visual-deco"></div>
        </div>
        <?php endif; ?>
    </div>

    <!-- ============================================
         PROJECT BODY - Description
         ============================================ -->
    <?php if (!empty($bodyParagraphs)): ?>
    <section class="project-body">
        <div class="body-label">About the project</div>
        <div class="body-text">
            <?php foreach ($bodyParagraphs as $paragraph): ?>
            <p><?= e($paragraph) ?></p>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================
         IMAGE GALLERY
         ============================================ -->
    <?php if (!empty($gallery)): ?>
    <div class="project-gallery">
        <?php foreach ($gallery as $i => $item): ?>
        <div class="gallery-item gallery-item--<?= $i + 1 ?>">
            <?php if (!empty($item['image'])): ?>
            <div class="gallery-item-inner">
                <img src="<?= e($item['image']) ?>" alt="<?= $title ?> gallery <?= $i + 1 ?>" loading="lazy" style="width:100%;height:auto;display:block;" />
            </div>
            <?php else: ?>
            <div class="gallery-item-inner" style="background: <?= e($item['gradient'] ?? '') ?>;"></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ============================================
         NEXT PROJECT - "Next project"
         ============================================ -->
    <section class="next-project">
        <div class="next-label">Next project</div>
        <a href="<?= $nextHref ?>" class="next-title-link">
            <span class="next-title"><?= $nextTitle ?></span>
            <div class="next-arrow">↗</div>
            <div class="next-cursor">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
        </a>
    </section>

    <!-- ============================================
         FOOTER
         ============================================ -->
<?php require __DIR__ . '/components/footer.php'; ?>

<?php require __DIR__ . '/components/scripts.php'; ?>

        /* ============================================
           HERO - Title clip-path reveal (curtain wipe up)
           ============================================ */
        gsap.from('.project-title', {
            clipPath: 'inset(100% 0 0 0)',
            duration: 1,
            ease: 'power4.out',
            delay: 0.2
        });

        /* ============================================
           HERO - Badge & description fade in
           ============================================ */
        gsap.fromTo('.project-badge',
            { opacity: 0, y: 15 },
            { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', delay: 0.8 }
        );

        gsap.fromTo('.project-description',
            { opacity: 0, y: 15 },
            { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out', delay: 0.95 }
        );

        /* ============================================
           HERO - Metadata stagger reveal
           ============================================ */
        gsap.fromTo('.meta-item',
            { opacity: 0, y: 20 },
            { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out', delay: 0.5 }
        );

        /* ============================================
           PROJECT VISUAL - Parallax on scroll
           ============================================ */
        const visualEl = document.querySelector('.project-visual') || document.querySelector('.visual-carousel');
        if (visualEl) {
            gsap.to(visualEl, {
                scrollTrigger: {
                    trigger: '.project-visual-wrap',
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true
                },
                y: -60
            });
        }

        /* ============================================
           IMAGE CAROUSEL
           ============================================ */
        (() => {
            const carousel = document.getElementById('visualCarousel');
            if (!carousel) return;

            const track = document.getElementById('carouselTrack');
            const dots  = carousel.querySelectorAll('.carousel-dot');
            const prev  = document.getElementById('carouselPrev');
            const next  = document.getElementById('carouselNext');
            const total = dots.length;
            let current = 0;

            function goTo(index) {
                current = ((index % total) + total) % total;
                track.style.transform = `translateX(-${current * 100}%)`;
                dots.forEach((d, i) => d.classList.toggle('active', i === current));
            }

            prev.addEventListener('click', () => goTo(current - 1));
            next.addEventListener('click', () => goTo(current + 1));
            dots.forEach(dot => {
                dot.addEventListener('click', () => goTo(parseInt(dot.dataset.index)));
            });

            // Optional: auto-advance every 5s
            let autoPlay = setInterval(() => goTo(current + 1), 5000);
            carousel.addEventListener('mouseenter', () => clearInterval(autoPlay));
            carousel.addEventListener('mouseleave', () => {
                autoPlay = setInterval(() => goTo(current + 1), 5000);
            });
        })();

        /* ============================================
           BODY TEXT - Scroll reveal per paragraph
           ============================================ */
        gsap.utils.toArray('.body-text p').forEach(p => {
            gsap.from(p, {
                scrollTrigger: {
                    trigger: p,
                    start: 'top 88%'
                },
                opacity: 0, y: 30,
                duration: 0.8,
                ease: 'power2.out'
            });
        });

        /* ============================================
           BODY LABEL - Scroll reveal
           ============================================ */
        gsap.from('.body-label', {
            scrollTrigger: {
                trigger: '.project-body',
                start: 'top 85%'
            },
            opacity: 0, y: 15,
            duration: 0.6,
            ease: 'power2.out'
        });

        /* ============================================
           GALLERY - Scroll reveal with stagger
           ============================================ */
        gsap.fromTo('.gallery-item',
            { opacity: 0, y: 40 },
            {
                scrollTrigger: {
                    trigger: '.project-gallery',
                    start: 'top 85%'
                },
                opacity: 1, y: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: 'power2.out'
            }
        );

        /* ============================================
           GALLERY - Orientation-based layout
           ============================================ */
        (() => {
            const items = document.querySelectorAll('.gallery-item');
            let pending = 0;

            items.forEach(item => {
                const img = item.querySelector('img');
                if (!img) return;

                function applyLayout() {
                    if (img.naturalWidth && img.naturalHeight) {
                        const ratio = img.naturalWidth / img.naturalHeight;
                        // landscape (wider than tall) → span both columns
                        if (ratio >= 1) {
                            item.classList.add('landscape');
                        }
                    }
                }

                if (img.complete && img.naturalWidth) {
                    applyLayout();
                } else {
                    pending++;
                    img.addEventListener('load', () => { applyLayout(); pending--; });
                    img.addEventListener('error', () => { pending--; });
                }
            });
        })();

        /* ============================================
           GALLERY - Hover scale with GSAP
           ============================================ */
        document.querySelectorAll('.gallery-item').forEach(item => {
            const inner = item.querySelector('.gallery-item-inner');
            item.addEventListener('mouseenter', () => {
                gsap.to(inner, { scale: 1.02, duration: 0.5, ease: 'power2.out' });
            });
            item.addEventListener('mouseleave', () => {
                gsap.to(inner, { scale: 1, duration: 0.5, ease: 'power2.out' });
            });
        });

        /* ============================================
           NEXT PROJECT - Hover animation + cursor
           ============================================ */
        (() => {
            const mainCursor = document.getElementById('cursor');
            const link = document.querySelector('.next-title-link');
            const title = link?.querySelector('.next-title');
            const nextCursor = link?.querySelector('.next-cursor');
            if (!link || !nextCursor) return;

            const xTo = gsap.quickTo(nextCursor, 'x', { duration: 0.35, ease: 'power3' });
            const yTo = gsap.quickTo(nextCursor, 'y', { duration: 0.35, ease: 'power3' });

            link.addEventListener('mousemove', (e) => {
                const rect = link.getBoundingClientRect();
                xTo(e.clientX - rect.left);
                yTo(e.clientY - rect.top);
            });

            link.addEventListener('mouseenter', (e) => {
                const rect = link.getBoundingClientRect();
                gsap.set(nextCursor, {
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top
                });
                gsap.to(nextCursor, { opacity: 1, scale: 1, duration: 0.3, ease: 'power2.out' });
                gsap.to(title, { y: -5, duration: 0.3, ease: 'power2.out' });
                if (mainCursor) mainCursor.style.opacity = '0';
            });

            link.addEventListener('mouseleave', () => {
                gsap.to(nextCursor, { opacity: 0, scale: 0.5, duration: 0.25, ease: 'power2.in' });
                gsap.to(title, { y: 0, duration: 0.3, ease: 'power2.out' });
                if (mainCursor) mainCursor.style.opacity = '1';
            });
        })();

        /* ============================================
           NEXT PROJECT SECTION - Scroll reveal
           ============================================ */
        gsap.from('.next-label', {
            scrollTrigger: {
                trigger: '.next-project',
                start: 'top 85%'
            },
            opacity: 0, y: 15,
            duration: 0.6,
            ease: 'power2.out'
        });

        gsap.from('.next-title-link', {
            scrollTrigger: {
                trigger: '.next-project',
                start: 'top 80%'
            },
            opacity: 0, y: 30,
            duration: 0.8,
            ease: 'power2.out',
            delay: 0.1
        });

<?php require __DIR__ . '/components/end.php'; ?>
