<?php
require_once __DIR__ . '/components/helpers.php';
$data = loadProjectsData();
$projects = $data['projects'];

// Group project titles by category for the index cards
$categories = ['Cybersecurity' => [], 'Cryptography' => [], 'networking' => [], 'Development' => []];
foreach ($projects as $p) {
    $topic = $p['topic'] ?? '';
    if (stripos($topic, 'cybersecurity') !== false) {
        $categories['Cybersecurity'][] = $p['title'];
    } elseif (stripos($topic, 'cryptography') !== false) {
        $categories['Cryptography'][] = $p['title'];
    } elseif (stripos($topic, 'networking') !== false) {
        $categories['networking'][] = $p['title'];
    } else {
        $categories['Development'][] = $p['title'];
    }
}

$pageTitle = 'Developer';
$isHome = true;
require __DIR__ . '/components/head.php';
?>

        /* ============================================
           INDEX - Page-specific overrides
           ============================================ */
        .cursor {
            border-color: rgba(240, 237, 232, 0.6);
            background-color: var(--cursor-color);
        }

        .cursor.hover {
            border-color: rgba(240, 237, 232, 0.9);
            background: rgba(240, 237, 232, 0.08);
        }

        .theme-toggle {
            mix-blend-mode: normal;
            isolation: isolate;
        }

        /* ============================================
       HERO SECTION
       ============================================ */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding-top: 6rem;
            position: relative;
        }

        .hero-heading {
            font-size: clamp(3rem, 9vw, 8.5rem);
            font-weight: 700;
            line-height: 1.05;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        .hero-line {
            display: block;
            overflow: hidden;
        }

        .hero-line-inner {
            display: block;
            will-change: transform;
        }

        .scroll-indicator {
            position: absolute;
            bottom: clamp(2rem, 4vh, 4rem);
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0.5;
        }

        .scroll-indicator span {
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .scroll-arrow {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, transparent, var(--color-text-primary));
            position: relative;
        }

        .scroll-arrow::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            border-right: 1px solid var(--color-text-primary);
            border-bottom: 1px solid var(--color-text-primary);
            transform: translateX(-50%) rotate(45deg);
        }

        /* ============================================
       INTRO / TAGLINE SECTION
       ============================================ */
        .intro {
            padding: clamp(6rem, 12vh, 12rem) 0;
        }

        .intro-text {
            font-size: clamp(1.5rem, 3.5vw, 3.2rem);
            font-weight: 400;
            line-height: 1.4;
            max-width: 1000px;
        }

        .intro-text .accent {
            font-size: 1.05em;
        }

        .reveal-line {
            overflow: hidden;
            display: block;
        }

        .reveal-line-inner {
            display: block;
            will-change: transform;
        }

        /* ============================================
       projects / PORTFOLIO SECTION
       ============================================ */
        .projects {
            padding: clamp(4rem, 8vh, 8rem) 0;
        }

        .projects-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: clamp(2rem, 3.5vh, 3rem);
        }

        .projects-header-title {
            font-size: clamp(1.6rem, 2.5vw, 2.2rem);
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .projects-header-link {
            font-size: 0.95rem;
            font-weight: 400;
            color: var(--color-text-primary);
            position: relative;
            padding-bottom: 2px;
        }

        .projects-header-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: var(--color-border);
            transition: background 0.3s;
        }

        .projects-header-link:hover::after {
            background: var(--color-text-primary);
        }

        .projects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .projects-row {
            display: grid;
            gap: 16px;
        }

        .projects-row:first-child {
            grid-template-columns: 3fr 2fr;
        }

        .projects-row:last-child {
            grid-template-columns: 10fr 11fr;
        }

        .proj-card {
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            height: 300px;
            cursor: none;
            will-change: transform;
        }

        /* --- Shared: Card background layer --- */
        .card-bg {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            z-index: 0;
        }

        /* --- Shared: Dark overlay (hover only) --- */
        .card-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            opacity: 0;
            pointer-events: none;
            z-index: 3;
        }

        /* --- Shared: Cursor that follows mouse (hover only) --- */
        .card-cursor {
            position: absolute;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.12);
            border: 1.5px solid rgba(255, 255, 255, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            scale: 0.5;
            pointer-events: none;
            transform: translate(-50%, -50%);
            z-index: 10;
            will-change: transform, opacity;
        }

        .card-cursor svg {
            width: 22px;
            height: 22px;
        }

        /* --- Shared: Card content (bottom text) --- */
        .card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: clamp(1.2rem, 2vw, 2rem);
            z-index: 5;
            pointer-events: none;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.45) 0%, rgba(0, 0, 0, 0) 100%);
        }

        .card-tags {
            display: flex;
            gap: 8px;
            margin-bottom: 0.75rem;
        }

        .card-tag {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            padding: 5px 14px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .card-title {
            font-size: clamp(1.5rem, 2.2vw, 2.2rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
        }

        .card-title em {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 400;
        }

        /* --- Shared: Large centered category title --- */
        .card-hero-title {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            pointer-events: none;
        }

        .card-hero-title span {
            font-size: clamp(2.5rem, 6vw, 6rem);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            line-height: 1;
            text-align: center;
        }

        /* --- Card 1: Cybersecurity --- */
        .proj-cybresec .card-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3)), url("./assets/qowe.webp");
            background-size: cover;
        }

        /* --- Card 2: Cryptography --- */
        .proj-crypto .card-bg {
            background: linear-gradient(90deg, rgba(30, 30, 60, 1) 0%, rgba(60, 40, 100, 1) 48%, rgba(90, 60, 140, 1) 99%);
        }

        .proj-crypto .card-content {
            background: none;
        }

        .proj-crypto .card-title {
            color: #e8d5ff;
        }

        .proj-crypto .card-overlay {
            background: rgba(0, 0, 0, 0.35);
        }

        /* --- Card 3: networking --- */
        .proj-vdbn .card-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3)), url("./assets/bcka.webp");
            background-size: cover;
        }

        .proj-vdbn .card-content {
            background: none;
        }

        .proj-vdbn .card-title {
            color: #1a1a1a;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .proj-vdbn .card-overlay {
            background: rgba(0, 0, 0, 0.3);
        }

        .proj-vdbn-sun-wrap {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            pointer-events: none;
        }

        .proj-vdbn-sun {
            width: clamp(120px, 14vw, 180px);
            height: clamp(120px, 14vw, 180px);
        }

        /* --- Card 4: Development --- */
        .proj-dev .card-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3)), url("./assets/lskp.webp");
            background-size: cover;
            background-position: center;
                
        }

        .proj-dev .card-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 30% 40%, rgba(80, 120, 200, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(200, 100, 50, 0.1) 0%, transparent 50%);
        }

        /* ============================================
       MARQUEE / TICKER
       ============================================ */
        .marquee {
            padding: clamp(3rem, 6vh, 6rem) 0;
            overflow: hidden;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }

        .marquee-track {
            display: flex;
            white-space: nowrap;
            will-change: transform;
        }

        .marquee-text {
            font-size: clamp(2.5rem, 6vw, 5.5rem);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            color: var(--color-text-muted);
            opacity: 0.2;
            padding-right: 2rem;
            flex-shrink: 0;
        }

        .marquee-text .separator {
            display: inline-block;
            margin: 0 clamp(1rem, 2vw, 2.5rem);
            opacity: 0.5;
        }

        /* ============================================
       ABOUT SECTION
       ============================================ */
        .about {
            padding: clamp(6rem, 12vh, 14rem) 0;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: clamp(3rem, 6vw, 8rem);
            align-items: start;
        }

        .about-heading {
            font-size: clamp(2.5rem, 6vw, 6rem);
            font-weight: 700;
            line-height: 1.08;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }

        .about-heading-line {
            display: block;
            overflow: hidden;
        }

        .about-heading-inner {
            display: block;
        }

        .about-body {
            display: flex;
            flex-direction: column;
            gap: clamp(1.5rem, 2.5vw, 2.5rem);
            padding-top: 0.5rem;
        }

        .about-body p {
            font-size: clamp(0.95rem, 1.2vw, 1.15rem);
            line-height: 1.75;
            color: var(--color-text-secondary);
        }

        /* ============================================
       edu SECTION
       ============================================ */
        .edu {
            padding: clamp(6rem, 10vh, 12rem) 0;
        }

        .edu-heading {
            font-size: clamp(2.5rem, 6vw, 6rem);
            font-weight: 700;
            line-height: 1.08;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            margin-bottom: clamp(3rem, 5vh, 5rem);
        }

        .edu-heading-line {
            display: block;
            overflow: hidden;
        }

        .edu-heading-inner {
            display: block;
        }

        .edu-list {
            max-width: 900px;
        }

        .edu-item {
            border-bottom: 1px solid var(--color-border);
        }

        .edu-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: clamp(1.2rem, 2vw, 2rem) 0;
            cursor: none;
            -webkit-user-select: none;
            user-select: none;
        }

        .edu-question-text {
            font-size: clamp(1.1rem, 1.8vw, 1.5rem);
            font-weight: 500;
            letter-spacing: -0.01em;
            padding-right: 2rem;
        }

        .edu-question-date {
            font-size: clamp(0.75rem, 0.9vw, 0.85rem);
            color: var(--color-text-muted);
            letter-spacing: 0.04em;
            margin-left: auto;
            margin-right: clamp(1rem, 2vw, 2rem);
            flex-shrink: 0;
            white-space: nowrap;
        }

        .edu-icon {
            width: clamp(28px, 3vw, 40px);
            height: clamp(28px, 3vw, 40px);
            flex-shrink: 0;
            border-radius: 50%;
            border: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94), border-color 0.3s;
        }

        .edu-icon::before,
        .edu-icon::after {
            content: '';
            position: absolute;
            background: var(--color-text-primary);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .edu-icon::before {
            width: 12px;
            height: 1px;
        }

        .edu-icon::after {
            width: 1px;
            height: 12px;
        }

        .edu-item.active .edu-icon {
            border-color: var(--color-text-muted);
        }

        .edu-item.active .edu-icon::after {
            transform: rotate(90deg);
        }

        .edu-answer {
            overflow: hidden;
            height: 0;
        }

        .edu-answer-inner {
            padding-bottom: clamp(1.2rem, 2vw, 2rem);
        }

        .edu-answer p {
            font-size: clamp(0.9rem, 1.1vw, 1.05rem);
            line-height: 1.8;
            color: var(--color-text-muted);
            max-width: 700px;
        }

        /* ============================================
       GENERAL REVEAL UTILITY
       ============================================ */
        .reveal-wrap {
            overflow: hidden;
            display: block;
        }

        .reveal-inner {
            display: block;
            will-change: transform;
        }

        /* ============================================
       RESPONSIVE
       ============================================ */
        @media (max-width: 768px) {
            .hero {
                padding-top: 5rem;
                padding-bottom: 5rem;
                min-height: 100svh;
            }

            .hero-heading {
                font-size: clamp(3.2rem, 14vw, 5.5rem);
                line-height: 1.0;
            }

            .scroll-indicator {
                bottom: 1.5rem;
            }

            .intro-text {
                font-size: clamp(1.2rem, 5vw, 1.8rem);
                line-height: 1.55;
            }

            .projects-header {
                margin-bottom: 1.2rem;
            }

            .projects-header-title {
                font-size: 1.3rem;
            }

            .projects-row:first-child,
            .projects-row:last-child {
                grid-template-columns: 1fr;
            }

            .proj-card {
                height: 260px;
                border-radius: 12px;
                cursor: auto;
            }

            .card-cursor {
                display: none;
            }

            .card-hero-title span {
                font-size: clamp(2.2rem, 11vw, 4rem);
            }

            .card-tag {
                font-size: 0.65rem;
                padding: 4px 10px;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .marquee {
                padding: clamp(2rem, 4vh, 3rem) 0;
            }

            .about {
                padding: clamp(4rem, 8vh, 6rem) 0;
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .about-heading {
                font-size: clamp(2.2rem, 10vw, 3.5rem);
            }

            .about-body p {
                font-size: 1rem;
                line-height: 1.7;
            }

            .edu {
                padding: clamp(3.5rem, 7vh, 5rem) 0;
            }

            .edu-heading {
                font-size: clamp(2.2rem, 10vw, 3.5rem);
                margin-bottom: clamp(2rem, 4vh, 3rem);
            }

            .edu-question {
                cursor: auto;
            }

            .edu-question-text {
                font-size: 1rem;
                padding-right: 1rem;
            }

            .edu-question-date {
                display: none;
            }

            .edu-answer p {
                font-size: 0.9rem;
                line-height: 1.75;
            }

            .index-contactme-btn{;    
            width:75% !important;
            }
        }
    </style>
</head>

<body>

<?php require __DIR__ . '/components/nav.php'; ?>

    <!-- ============================================
       HERO SECTION
       ============================================ -->
    <section class="hero">
        <div class="container">
            <h1 class="hero-heading">
                <span class="hero-line">
                    <span class="hero-line-inner"><span class="accent">MA13W</span></span>
                </span>
                <span class="hero-line">
                    <span class="hero-line-inner">MATTEO CALI</span>
                </span>
            </h1>
        </div>
        <div class="scroll-indicator" id="scrollIndicator">
            <span>Scroll</span>
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- ============================================
       INTRO / TAGLINE SECTION
       ============================================ -->
    <section class="intro">
        <div class="container">
            <p class="intro-text">
                <span class="reveal-line"><span class="reveal-inner">I'm a <span class="accent">developer</span> and <span class="accent">student</span> passionate about <span class="accent">development</span> and <span class="accent">cybersecurity</span>.</span></span>
                <span class="reveal-line"><span class="reveal-inner">I create websites and apps where <span class="accent">design</span> and <span class="accent">functionality</span> meet.</span></span>
            </p>
        </div>
    </section>

    <!-- ============================================
       projects / PORTFOLIO SECTION
       ============================================ -->
    <section class="projects" id="projects">
        <div class="container">
            <!-- projects Header -->
            <div class="projects-header">
                <h2 class="projects-header-title">Projects</h2>
                <a href="projects.php" class="projects-header-link" data-hover>View all</a>
            </div>

            <!-- projects Grid -->
            <div class="projects-grid">
                <!-- Row 1: Cybersecurity (large) + AI (small) -->
                <div class="projects-row">
                    <!-- Card 1: Cybersecurity -->
                    <a href="projects.php?topic=cybersecurity" class="proj-card proj-cybresec" data-hover>
                        <div class="card-bg"></div>
                        <div class="card-hero-title"><span style="color:#fff;">Cybersecurity</span></div>
                        <div class="card-overlay"></div>
                        <div class="card-cursor">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($categories['Cybersecurity'])): ?>
                            <div class="card-tags">
                                <?php foreach ($categories['Cybersecurity'] as $name): ?>
                                <span class="card-tag"><?= htmlspecialchars($name) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <!-- <div class="card-title">Cyber<em>security</em></div> -->
                        </div>
                    </a>

                    <!-- Card 2: crypto -->
                    <a href="projects.php?topic=cryptography" class="proj-card proj-crypto" data-hover>
                        <div class="card-bg"></div>
                        <div class="card-hero-title"><span style="color:#fff;">Crypto</span></div>
                        <div class="card-overlay"></div>
                        <div class="card-cursor">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($categories['Cryptography'])): ?>
                            <div class="card-tags">
                                <?php foreach ($categories['Cryptography'] as $name): ?>
                                <span class="card-tag"><?= htmlspecialchars($name) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <!-- <div class="card-title" style="color:#1a4a2e;">AI</div> -->
                        </div>
                    </a>
                </div>

                <!-- Row 2: networking (small) + Development (large) -->
                <div class="projects-row">
                    <!-- Card 3: networking -->
                    <a href="projects.php?topic=networking" class="proj-card proj-vdbn" data-hover>
                        <div class="card-bg"></div>
                        <div class="card-hero-title"><span style="color:#fff;">Networking</span></div>
                        <div class="card-overlay"></div>
                        <div class="card-cursor">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($categories['networking'])): ?>
                            <div class="card-tags">
                                <?php foreach ($categories['networking'] as $name): ?>
                                <span class="card-tag"><?= htmlspecialchars($name) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <!-- <div class="card-title" style="color:#1a1a1a; font-size:0.85rem; font-weight:600; letter-spacing:0.06em; text-transform:uppercase;">networking</div> -->
                        </div>
                    </a>

                    <!-- Card 4: Development -->
                    <a href="projects.php?topic=development" class="proj-card proj-dev" data-hover>
                        <div class="card-bg"></div>
                        <div class="card-hero-title"><span style="color:#fff;">Development</span></div>
                        <div class="card-overlay"></div>
                        <div class="card-cursor">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M7 17L17 7M17 7H7M17 7V17" stroke="white" stroke-width="2.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="card-content">
                            <?php if (!empty($categories['Development'])): ?>
                            <div class="card-tags">
                                <?php foreach ($categories['Development'] as $name): ?>
                                <span class="card-tag"><?= htmlspecialchars($name) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                            <!-- <div class="card-title">Development</div> -->
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
       MARQUEE / TICKER STRIP
       ============================================ -->
    <section class="marquee">
        <div class="marquee-track" id="marqueeTrack">
            <div class="marquee-text">CREATIVE <span class="separator">-</span> USEFULL <span
                    class="separator">-</span> IMMERSIVE <span class="separator">-</span> CREATIVE <span
                    class="separator">-</span> USEFULL <span class="separator">-</span> IMMERSIVE <span
                    class="separator">-</span></div>
            <div class="marquee-text">CREATIVE <span class="separator">-</span> USEFULL <span
                    class="separator">-</span> IMMERSIVE <span class="separator">-</span> CREATIVE <span
                    class="separator">-</span> USEFULL <span class="separator">-</span> IMMERSIVE <span
                    class="separator">-</span></div>
        </div>
    </section>

    <!-- ============================================
       ABOUT SECTION
       ============================================ -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-grid">
                <h2 class="about-heading">
                    <span class="about-heading-line">
                        <span class="about-heading-inner">The story</span>
                    </span>
                    <span class="about-heading-line">
                        <span class="about-heading-inner">behind</span>
                    </span>
                    <span class="about-heading-line">
                        <span class="about-heading-inner"><span class="accent">mattew</span></span>
                    </span>
                </h2>
                <div class="about-body">
                    <p>I'm Matteo, an 18-year-old Computer Science student from Milan with a deep passion for cybersecurity, web development, and building things that actually matter.</p>
                    <p>From designing 100+ CTF challenges to training 200+ students in ethical hacking, I turn complex security concepts into real learning experiences; one exploit at a time.</p>
                    <p>Whether it's a SOC internship, an Erasmus+ stint in Dublin, or a decentralized protocol on GitHub; I chase experiences that push me forward and shape who I'm becoming.</p>
                    <a href="contact.php" class="btn btn-primary index-contactme-btn" id="contactBtn" style="margin-top:2.5rem; display:inline-flex; align-items:center; gap:0.8rem; font-size:1.1rem; font-weight:600; padding:0.9em 2.2em; border-radius:12px; background:var(--color-text-primary); color:var(--color-accent-text); box-shadow:0 2px 16px rgba(0,0,0,0.08); letter-spacing:0.04em; text-transform:uppercase; border:none; transition:background 0.2s, color 0.2s; width:40%;">
                        Contact Me
                        <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M7 17L17 7M17 7H7M17 7V17" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>

                    <script>
                        const contactBtn = document.getElementById('contactBtn');
                        const arrowIcon = contactBtn.querySelector('.arrow-icon');

                        contactBtn.addEventListener('mouseenter', () => {
                            gsap.to(arrowIcon, {
                                x: 4,
                                y: -4,
                                duration: 0.4,
                                ease: 'power2.out'
                            });
                        });

                        contactBtn.addEventListener('mouseleave', () => {
                            gsap.to(arrowIcon, {
                                x: 0,
                                y: 0,
                                duration: 0.4,
                                ease: 'power2.out'
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
       edu SECTION
       ============================================ -->
    <section class="edu" id="edu">
        <div class="container">
            <h2 class="edu-heading">
                <span class="edu-heading-line">
                    <span class="edu-heading-inner">MY IT</span>
                </span>
                <span class="edu-heading-line">
                    <span class="edu-heading-inner"><span class="accent">Journey</span></span>
                </span>
            </h2>
            <div class="edu-list">
                <!-- work 1 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">CTF Challenge Designer & Instructor - Team Radium</span>
                        <span class="edu-question-date">Sep 2023 - Now</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>
                                - Authored 100+ Capture The Flag (CTF) challenges ranging from beginner-friendly exercises for high-school students to advanced scenarios (web, crypto, network, reverse engineering, osint).<br>
                                - Trained over 200 high-school students in cybersecurity and ethical hacking through after-school workshops and curriculum-integrated classes.<br>
                                - Managed full event lifecycle: challenge creation, platform deployment, live hosting, and participant mentoring.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- work 2 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">IT Support Technican - Back from the Future</span>
                        <span class="edu-question-date">Sep 2025</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>During my internship at LaptopLab, Back From the Future in Bray, sponsored by the Erasmus+ program, I carried out technical support activities both in-store and on-site at client companies. 
I was involved in hardware repair and system upgrades on Windows PCs and Apple devices, including component replacement, diagnostics, and BIOS troubleshooting. I also managed data migration and backup operations, ensuring secure transitions between devices and operating systems. 
In addition, I worked on the configuration of virtualized environments on Windows machines and resolved connectivity issues with printers and peripherals across different platforms. 
This experience allowed me to strengthen my technical expertise while gaining direct exposure to customer support in real operational contexts.</p>                        </div>
                    </div>
                </div>
                <!-- work 3 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">SOC & Cybersecurity Trainee - Npo Sistemi</span>
                        <span class="edu-question-date">Jun 2025</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>During my internship at Npo Sistemi, I collaborated with the Cyber Security Consulting (CSC) and Service Operations Center (SOC) teams, following a training path focused on Cybersecurity and Networking. I participated in Vulnerability Assessment and Penetration Testing (VA-PT) activities as well as Managed Detection and Response (MDR), in addition to supporting the monitoring and configuration of Zabbix and the Sophos suite. Moreover, together with the team, I deepened my understanding of concepts related to cloud, virtualization, and hybrid infrastructure.</p>
                        </div>
                    </div>
                </div>
                <!-- edu 1 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">IT Student - ITSOS Marie Curie</span>
                        <span class="edu-question-date">2021 - 2026</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>I studied Computer Science and Telecommunication in Milan, Italy. During this time I developed a strong foundation in programming, databases, network systems and network security. I certified as IT Essentials and CCNA: Introduction to Networks. I also teach an afternoot course about cybersecurity, based on Jeopardy CTF.</p>
                        </div>
                    </div>
                </div>
                <!-- edu 2 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">Cyberchallenge.IT Student - Politecnico di milano</span>
                        <span class="edu-question-date">Mar 2026</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>I attended a national cybersecurity training program at Politecnico di Milano organized by Cyberchallenge.IT. We covered topics like web security, cryptography, binary exploitation, and reverse engineering.</p>
                        </div>
                    </div>
                </div>
                <!-- edu 3 -->
                <div class="edu-item">
                    <div class="edu-question" data-hover>
                        <span class="edu-question-text">English Camp - ESL Embassy Summer</span>
                        <span class="edu-question-date">Jul 2021</span>
                        <div class="edu-icon"></div>
                    </div>
                    <div class="edu-answer">
                        <div class="edu-answer-inner">
                            <p>I studied abroad at a college, fully immersing myself in an international environment and significantly improving my English.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================
       FOOTER
       ============================================ -->
<?php require __DIR__ . '/components/footer.php'; ?>

<?php require __DIR__ . '/components/scripts.php'; ?>

        /* ============================================
           HERO ANIMATION - Page Load Sequence
           ============================================ */
        (() => {
            const heroLines = document.querySelectorAll('.hero-line-inner');
            const tl = gsap.timeline({ delay: 0.3 });

            gsap.set(heroLines, { yPercent: 110 });

            tl.to(heroLines, {
                yPercent: 0,
                duration: 1.1,
                ease: 'power4.out',
                stagger: 0.12
            });

            // Scroll indicator fade in
            const indicator = document.getElementById('scrollIndicator');
            gsap.set(indicator, { opacity: 0, y: 10 });
            tl.to(indicator, {
                opacity: 0.5,
                y: 0,
                duration: 0.8,
                ease: 'power2.out'
            }, '-=0.3');

            // Bounce the scroll arrow
            gsap.to('#scrollIndicator .scroll-arrow', {
                y: 6,
                duration: 1.2,
                ease: 'power1.inOut',
                repeat: -1,
                yoyo: true
            });

            // Hide scroll indicator on scroll
            ScrollTrigger.create({
                trigger: '.intro',
                start: 'top 90%',
                onEnter: () => {
                    gsap.to(indicator, { opacity: 0, duration: 0.4 });
                }
            });
        })();

        /* ============================================
           INTRO TEXT - Scroll Reveal
           ============================================ */
        (() => {
            const lines = document.querySelectorAll('.intro .reveal-inner');

            gsap.set(lines, { yPercent: 110 });

            gsap.to(lines, {
                yPercent: 0,
                duration: 1,
                ease: 'power4.out',
                stagger: 0.08,
                scrollTrigger: {
                    trigger: '.intro',
                    start: 'top 80%',
                    toggleActions: 'play none none none'
                }
            });
        })();

        /* ============================================
           projects - Scroll Reveal & Hover Cursor
           ============================================ */
        (() => {
            // Header reveal
            gsap.from('.projects-header', {
                y: 20,
                opacity: 0,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '.projects',
                    start: 'top 85%'
                }
            });

            // Cards staggered reveal on scroll
            const cards = document.querySelectorAll('.proj-card');
            cards.forEach((card, i) => {
                gsap.from(card, {
                    y: 30,
                    opacity: 0,
                    duration: 0.9,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 88%'
                    },
                    delay: i * 0.15
                });
            });

            // Per-card: cursor follow + overlay + scale
            const mainCursor = document.getElementById('cursor');
            cards.forEach(card => {
                const cardCursor = card.querySelector('.card-cursor');
                const overlay = card.querySelector('.card-overlay');
                if (!cardCursor || !overlay) return;

                // GSAP quickTo for smooth cursor follow
                const xTo = gsap.quickTo(cardCursor, 'x', { duration: 0.4, ease: 'power3' });
                const yTo = gsap.quickTo(cardCursor, 'y', { duration: 0.4, ease: 'power3' });

                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    xTo(e.clientX - rect.left);
                    yTo(e.clientY - rect.top);
                });

                card.addEventListener('mouseenter', (e) => {
                    // Position cursor immediately on enter to avoid jump from 0,0
                    const rect = card.getBoundingClientRect();
                    gsap.set(cardCursor, {
                        x: e.clientX - rect.left,
                        y: e.clientY - rect.top
                    });
                    // Show card cursor, hide main cursor
                    gsap.to(cardCursor, { opacity: 1, scale: 1, duration: 0.3, ease: 'power2.out' });
                    gsap.to(overlay, { opacity: 1, duration: 0.4, ease: 'power2.out' });
                    gsap.to(card, { scale: 1.02, duration: 0.4, ease: 'power2.out' });
                    if (mainCursor) mainCursor.style.opacity = '0';
                });

                card.addEventListener('mouseleave', () => {
                    gsap.to(cardCursor, { opacity: 0, scale: 0.5, duration: 0.3, ease: 'power2.in' });
                    gsap.to(overlay, { opacity: 0, duration: 0.4, ease: 'power2.out' });
                    gsap.to(card, { scale: 1, duration: 0.4, ease: 'power2.out' });
                    if (mainCursor) mainCursor.style.opacity = '1';
                });
            });
        })();

        /* ============================================
           MARQUEE - Infinite Scroll
           ============================================ */
        (() => {
            const track = document.getElementById('marqueeTrack');
            const items = track.querySelectorAll('.marquee-text');

            // Duplicate if needed for seamless loop
            const firstItem = items[0];
            const width = firstItem.offsetWidth;

            gsap.to(track, {
                x: -width,
                duration: 18,
                ease: 'none',
                repeat: -1,
                modifiers: {
                    x: gsap.utils.unitize(x => parseFloat(x) % width)
                }
            });
        })();

        /* ============================================
           ABOUT - Scroll Reveal
           ============================================ */
        (() => {
            const headingInners = document.querySelectorAll('.about-heading-inner');

            gsap.set(headingInners, { yPercent: 110 });

            gsap.to(headingInners, {
                yPercent: 0,
                duration: 1,
                ease: 'power4.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.about',
                    start: 'top 75%'
                }
            });

            const paragraphs = document.querySelectorAll('.about-body p');
            paragraphs.forEach((p, i) => {
                gsap.from(p, {
                    y: 40,
                    opacity: 0,
                    duration: 0.9,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: p,
                        start: 'top 88%'
                    },
                    delay: i * 0.1
                });
            });
        })();

        /* ============================================
           edu - Scroll Reveal & Accordion
           ============================================ */
        (() => {
            // Heading reveal
            const headingInners = document.querySelectorAll('.edu-heading-inner');
            gsap.set(headingInners, { yPercent: 110 });

            gsap.to(headingInners, {
                yPercent: 0,
                duration: 1,
                ease: 'power4.out',
                stagger: 0.1,
                scrollTrigger: {
                    trigger: '.edu',
                    start: 'top 75%'
                }
            });

            // Accordion
            const items = document.querySelectorAll('.edu-item');

            items.forEach(item => {
                const question = item.querySelector('.edu-question');
                const answer = item.querySelector('.edu-answer');
                const inner = item.querySelector('.edu-answer-inner');

                // Stagger item reveal on scroll
                gsap.from(item, {
                    y: 30,
                    opacity: 0,
                    duration: 0.7,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: item,
                        start: 'top 90%'
                    }
                });

                question.addEventListener('click', () => {
                    const isOpen = item.classList.contains('active');

                    // Close all others
                    items.forEach(other => {
                        if (other !== item && other.classList.contains('active')) {
                            other.classList.remove('active');
                            gsap.to(other.querySelector('.edu-answer'), {
                                height: 0,
                                duration: 0.5,
                                ease: 'power3.inOut'
                            });
                        }
                    });

                    if (isOpen) {
                        item.classList.remove('active');
                        gsap.to(answer, {
                            height: 0,
                            duration: 0.5,
                            ease: 'power3.inOut'
                        });
                    } else {
                        item.classList.add('active');
                        // Measure the natural height
                        const naturalHeight = inner.offsetHeight;
                        gsap.to(answer, {
                            height: naturalHeight,
                            duration: 0.5,
                            ease: 'power3.inOut'
                        });
                    }
                });
            });
        })();

<?php require __DIR__ . '/components/end.php'; ?>