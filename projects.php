<?php
require_once __DIR__ . '/components/helpers.php';
$data = loadProjectsData();
$projects = $data['projects'];
usort($projects, fn($a, $b) => $a['order'] <=> $b['order']);

// Topic filter from query string
$topicFilter = isset($_GET['topic']) ? strtolower(trim($_GET['topic'])) : '';
$topicLabels = [
    'cybersecurity' => 'Cybersecurity',
    'cryptography'  => 'Cryptography',
    'networking'    => 'Networking',
    'development'   => 'Development',
];
if ($topicFilter && array_key_exists($topicFilter, $topicLabels)) {
    $projects = array_values(array_filter($projects, function ($p) use ($topicFilter) {
        $topic = strtolower($p['topic'] ?? '');
        switch ($topicFilter) {
            case 'cybersecurity': return strpos($topic, 'cybersecurity') !== false;
            case 'cryptography':  return strpos($topic, 'cryptography') !== false;
            case 'networking':    return strpos($topic, 'networking') !== false;
            case 'development':    return strpos($topic, 'development') !== false;
            default: return true;
        }
    }));
} else {
    $topicFilter = '';
}
$activeLabel = $topicFilter ? $topicLabels[$topicFilter] : '';

$pageTitle = 'projects';
$isHome = false;
$cursorHoverSelector = '.navbar a, .footer a, [data-hover]:not(.project-row)';
require __DIR__ . '/components/head.php';
?>
        /* ============================================
           PAGE HERO
           ============================================ */
        .page-hero {
            padding: clamp(7rem, 14vh, 10rem) clamp(1.5rem, 4vw, 4rem) clamp(2rem, 4vh, 3rem);
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-title {
            font-size: clamp(5rem, 15vw, 14rem);
            font-weight: 800;
            line-height: 0.9;
            letter-spacing: -0.03em;
            text-transform: uppercase;
            display: inline-block;
            position: relative;
            color: var(--color-text-primary);
        }

        .title-dot {
            position: absolute;
            top: 10px;
            right: -20px;
            width: 10px;
            height: 10px;
            background: var(--color-text-primary);
            border-radius: 50%;
        }

        /* ============================================
           PROJECTS TABLE
           ============================================ */
        .projects-section {
            padding: clamp(1rem, 2vh, 1.5rem) clamp(1.5rem, 4vw, 4rem) clamp(5rem, 10vh, 8rem);
            max-width: 1400px;
            margin: 0 auto;
        }

        .table-header {
            display: grid;
            grid-template-columns: 3fr 1.2fr 1.2fr;
            padding: 0 0 clamp(12px, 1.5vw, 18px);
            color: var(--color-text-muted);
            font-size: 0.85rem;
            font-weight: 400;
            border-bottom: 1px solid var(--color-border);
        }

        .projects-list {
            display: flex;
            flex-direction: column;
        }

        /* --- Project Row --- */
        .project-row {
            display: grid;
            grid-template-columns: 3fr 1.2fr 1.2fr;
            align-items: center;
            padding: clamp(20px, 2.5vw, 32px) 0;
            border-bottom: 1px solid var(--color-border);
            text-decoration: none;
            cursor: none;
            position: relative;
            overflow: hidden;
            color: var(--color-text-primary);
            opacity: 1;
            /* Exclude opacity from the global theme-transition to avoid GSAP conflicts */
            transition: background-color 0.5s ease, color 0.5s ease, border-color 0.5s ease;
        }

        .project-row:last-child {
            border-bottom: 1px solid var(--color-border);
        }

        .project-name {
            font-size: clamp(1.4rem, 2.5vw, 2rem);
            font-weight: 700;
            color: var(--color-text-primary);
            letter-spacing: -0.01em;
            will-change: transform;
        }

        .project-services,
        .project-client {
            font-size: clamp(0.85rem, 1vw, 1rem);
            color: var(--color-text-secondary);
            font-weight: 400;
        }

        /* --- Row hover cursor (circle + arrow) --- */
        .row-cursor {
            position: absolute;
            width: 56px;
            height: 56px;
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

        .row-cursor svg {
            width: 18px;
            height: 18px;
        }

        /* ============================================
           TOPIC FILTER BADGE
           ============================================ */
        .filter-badge-wrap {
            margin-top: clamp(1rem, 2vh, 1.5rem);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .filter-label {
            font-size: 0.8rem;
            font-weight: 400;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--color-text-primary);
            color: var(--color-accent-text);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 6px 14px;
            border-radius: 50px;
        }

        .filter-badge-clear {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: inherit;
            opacity: 0.7;
            cursor: none;
            padding: 0;
            line-height: 1;
            transition: opacity 0.2s;
        }

        .filter-badge-clear:hover {
            opacity: 1;
        }

        .no-projects {
            padding: clamp(2rem, 4vh, 3rem) 0;
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: var(--color-text-muted);
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .page-hero {
                padding: clamp(5.5rem, 11vh, 7rem) 20px clamp(1rem, 2vh, 1.5rem);
            }

            .page-title {
                font-size: clamp(3.5rem, 18vw, 7rem);
                line-height: 0.9;
            }

            .title-dot {
                width: 7px;
                height: 7px;
                top: 8px;
                right: -12px;
            }

            .projects-section {
                padding: 0.5rem 20px clamp(3.5rem, 7vh, 5rem);
            }

            /* Hide Services and Topic columns on mobile */
            .table-header {
                grid-template-columns: 1fr;
            }

            .table-header span:nth-child(2),
            .table-header span:nth-child(3) {
                display: none;
            }

            .project-row {
                grid-template-columns: 1fr;
                padding: clamp(16px, 4vw, 24px) 0;
                cursor: auto;
            }

            .project-name {
                font-size: clamp(1.2rem, 5.5vw, 1.8rem);
            }

            .project-services,
            .project-client {
                display: none;
            }

            .row-cursor {
                display: none;
            }

            .filter-badge-wrap {
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .filter-badge {
                font-size: 0.7rem;
                padding: 5px 12px;
            }
        }
    </style>
</head>

<body>

<?php require __DIR__ . '/components/nav.php'; ?>

    <!-- ============================================
         PAGE HERO
         ============================================ -->
    <section class="page-hero">
        <h1 class="page-title">
            Projects<span class="title-dot"></span>
        </h1>
        <?php if ($activeLabel): ?>
        <div class="filter-badge-wrap">
            <span class="filter-label">Filtered by</span>
            <span class="filter-badge">
                <?= htmlspecialchars($activeLabel) ?>
                <a href="projects.php" class="filter-badge-clear" title="Clear filter" aria-label="Clear filter">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                        <path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </a>
            </span>
        </div>
        <?php endif; ?>
    </section>

    <!-- ============================================
         PROJECTS TABLE
         ============================================ -->
    <section class="projects-section">
        <!-- Table Header -->
        <div class="table-header">
            <span>Project</span>>
            <span>Services</span>
            <span>Topic</span>
        </div>

        <!-- Projects List (dynamically from JSON) -->
        <div class="projects-list">
            <?php if (empty($projects)): ?>
            <p class="no-projects">No projects found for this category yet - check back soon.</p>
            <?php endif; ?>
            <?php foreach ($projects as $p): ?>
            <?php
                $hasDetail = !empty($p['hero_description']);
                $href = $hasDetail ? 'project.php?slug=' . urlencode($p['slug']) : '#';
            ?>
            <a href="<?= $href ?>" class="project-row" data-hover>
                <div class="project-name"><?= e($p['title']) ?></div>
                <div class="project-services"><?= e($p['listing_services']) ?></div>
                <div class="project-client"><?= e($p['topic']) ?></div>
                <div class="row-cursor">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>


<?php require __DIR__ . '/components/footer.php'; ?>

<?php require __DIR__ . '/components/scripts.php'; ?>

        /* ============================================
           PAGE TITLE - Clip-path reveal on load
           ============================================ */
        gsap.from('.page-title', {
            clipPath: 'inset(100% 0 0 0)',
            duration: 1.1,
            ease: 'power4.out',
            delay: 0.25
        });

        /* ============================================
           TABLE HEADER - Fade in
           ============================================ */
        gsap.from('.table-header', {
            opacity: 0,
            y: 10,
            duration: 0.6,
            ease: 'power2.out',
            delay: 0.6
        });

        /* ============================================
           PROJECT ROWS - Staggered scroll reveal
           ============================================ */
        gsap.from('.project-row', {
            scrollTrigger: {
                trigger: '.projects-list',
                start: 'top 88%',
                once: true
            },
            y: 24,
            duration: 0.6,
            stagger: 0.1,
            ease: 'power2.out',
            clearProps: 'transform'
        });

        /* ============================================
           PROJECT ROW HOVER - Cursor follow + name shift
           ============================================ */
        (() => {
            const mainCursor = document.getElementById('cursor');
            const rows = document.querySelectorAll('.project-row');

            rows.forEach(row => {
                const rowCursor = row.querySelector('.row-cursor');
                const projectName = row.querySelector('.project-name');
                if (!rowCursor) return;

                const xTo = gsap.quickTo(rowCursor, 'x', { duration: 0.35, ease: 'power3' });
                const yTo = gsap.quickTo(rowCursor, 'y', { duration: 0.35, ease: 'power3' });

                row.addEventListener('mousemove', (e) => {
                    const rect = row.getBoundingClientRect();
                    xTo(e.clientX - rect.left);
                    yTo(e.clientY - rect.top);
                });

                row.addEventListener('mouseenter', (e) => {
                    const rect = row.getBoundingClientRect();
                    gsap.set(rowCursor, {
                        x: e.clientX - rect.left,
                        y: e.clientY - rect.top
                    });
                    gsap.to(rowCursor, { opacity: 1, scale: 1, duration: 0.3, ease: 'power2.out' });
                    gsap.to(projectName, { x: 12, duration: 0.3, ease: 'power2.out' });
                    if (mainCursor) mainCursor.style.opacity = '0';
                });

                row.addEventListener('mouseleave', () => {
                    gsap.to(rowCursor, { opacity: 0, scale: 0.5, duration: 0.25, ease: 'power2.in' });
                    gsap.to(projectName, { x: 0, duration: 0.3, ease: 'power2.out' });
                    if (mainCursor) mainCursor.style.opacity = '1';
                });
            });
        })();

<?php require __DIR__ . '/components/end.php'; ?>
