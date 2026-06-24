<?php
/**
 * Shared <head> component
 *
 * Expects:
 *   $pageTitle   (string) - page title suffix
 *   $pageDesc    (string) - optional custom meta description
 *   $pageImage   (string) - optional OG image URL (absolute)
 *   $pageUrl     (string) - optional canonical URL
 */
$pageTitle = $pageTitle ?? 'Portfolio';
$siteUrl   = 'https://calimatteo.it';
$siteName  = 'Matteo Cali – Web Developer & Cybersecurity';

// Per-page overrides with smart defaults
$metaDesc  = $pageDesc  ?? 'Matteo Cali (mattew) – Web developer and software engineer with a passion for cybersecurity and networking. Explore my projects and work.';
$metaImage = $pageImage ?? $siteUrl . '/assets/og-image.jpg'; // Create a 1200×630 image
$metaUrl   = $pageUrl   ?? $siteUrl . '/' . ltrim($_SERVER['REQUEST_URI'] ?? '', '/');
$fullTitle = 'Matteo Cali – ' . e($pageTitle);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- =============================================
         PRIMARY META TAGS
         ============================================= -->
    <title><?= $fullTitle ?></title>
    <meta name="title"       content="<?= $fullTitle ?>" />
    <meta name="description" content="<?= e($metaDesc) ?>" />
    <meta name="author"      content="Matteo Cali" />
    <meta name="keywords"    content="Matteo Cali, mattew, web developer, cybersecurity, portfolio, software engineer, networking" />
    <meta name="robots"      content="index, follow" />
    <link rel="canonical"    href="<?= e($metaUrl) ?>" />

    <!-- =============================================
         OPEN GRAPH / FACEBOOK
         ============================================= -->
    <meta property="og:type"        content="website" />
    <meta property="og:site_name"   content="<?= e($siteName) ?>" />
    <meta property="og:title"       content="<?= $fullTitle ?>" />
    <meta property="og:description" content="<?= e($metaDesc) ?>" />
    <meta property="og:url"         content="<?= e($metaUrl) ?>" />
    <meta property="og:image"       content="<?= e($metaImage) ?>" />
    <meta property="og:image:width"  content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt"   content="<?= $fullTitle ?>" />
    <meta property="og:locale"      content="en_US" />

    <!-- =============================================
         TWITTER / X CARD
         ============================================= -->
    <meta name="twitter:card"        content="summary_large_image" />
    <meta name="twitter:title"       content="<?= $fullTitle ?>" />
    <meta name="twitter:description" content="<?= e($metaDesc) ?>" />
    <meta name="twitter:image"       content="<?= e($metaImage) ?>" />
    <!-- <meta name="twitter:site"   content="@yourhandle" /> -->
    <!-- <meta name="twitter:creator" content="@yourhandle" /> -->

    <!-- =============================================
         STRUCTURED DATA – Person schema (JSON-LD)
         ============================================= -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "Matteo Cali",
        "alternateName": "mattew",
        "url": "<?= $siteUrl ?>",
        "image": "<?= e($metaImage) ?>",
        "jobTitle": "Web Developer & Cybersecurity Enthusiast",
        "description": "<?= e($metaDesc) ?>",
        "sameAs": [
            "https://github.com/ma13w",
            "https://linkedin.com/in/matteo-cali"
        ]
    }
    </script>

    <!-- =============================================
         HREFLANG – only add alternate if you have
         a real translated version of each page
         ============================================= -->
    <link rel="alternate" hreflang="en" href="<?= e($metaUrl) ?>" />
    <link rel="alternate" hreflang="x-default" href="<?= $siteUrl ?>" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;1,9..40,400&family=Playfair+Display:ital,wght@1,400;1,500;1,600&display=swap"
        rel="stylesheet" />

    <!-- =============================================
         FAVICONS
         ============================================= -->
   <link rel="icon" type="image/png" href="/assets/favicon.ico/favicon-96x96.png" sizes="96x96" />
   <link rel="icon" type="image/svg+xml" href="/assets/favicon.ico/favicon.svg" />
   <link rel="shortcut icon" href="/assets/favicon.ico/favicon.ico" />
   <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon.ico/apple-touch-icon.png" />
   <meta name="apple-mobile-web-app-title" content="Cali Portfolio" />
   <link rel="manifest" href="/assets/favicon.ico/site.webmanifest" />

   <script>
        // Applica tema prima del render per evitare flash
        (function () {
            var t = localStorage.getItem('mattew-theme');
            if (!t) {
                t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();
    </script>

    <style>
        /* ============================================
           THEME SYSTEM - CSS Custom Properties
           ============================================ */
        :root {
            /* DEFAULT: tema chiaro */
            --color-bg: #f0f0ec;
            --color-bg-secondary: #e8e8e4;
            --color-surface: #ffffff;
            --color-text-primary: #111111;
            --color-text-secondary: #666666;
            --color-text-muted: #999999;
            --color-border: #e0e0dc;
            --color-accent: #111111;
            --color-accent-text: #ffffff;
            --color-tag-bg: #111111;
            --color-tag-text: #ffffff;
            --cursor-color: #111111;

            /* Transizione fluida su cambio tema */
            --theme-transition: background-color 0.5s ease, color 0.5s ease,
                border-color 0.5s ease, opacity 0.3s ease;
        }

        /* Tema scuro: attivato con classe sul <html> */
        html.dark {
            --color-bg: #0d0d0d;
            --color-bg-secondary: #161616;
            --color-surface: #1e1e1e;
            --color-text-primary: #f0ede8;
            --color-text-secondary: #a0a0a0;
            --color-text-muted: #666666;
            --color-border: #2a2a2a;
            --color-accent: #f0ede8;
            --color-accent-text: #0d0d0d;
            --color-tag-bg: #f0ede8;
            --color-tag-text: #0d0d0d;
            --cursor-color: #f0ede8;
        }

        /* ============================================
           RESET & BASE
           ============================================ */
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: var(--theme-transition);
        }

        ::-webkit-scrollbar {
            display: none;
        }

        html {
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth;
        }

        body {
            background: var(--color-bg);
            color: var(--color-text-primary);
            font-family: 'DM Sans', sans-serif;
            font-weight: 400;
            line-height: 1.6;
            overflow-x: hidden;
            cursor: none;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Noise / grain overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0.035;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 200px 200px;
            transition: opacity 0.5s ease;
        }

        html.dark body::after {
            opacity: 0.06;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        .container {
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 clamp(1.5rem, 4vw, 4rem);
        }

        /* Italic serif accent class */
        .accent {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 400;
            color: var(--color-text-secondary);
        }

        /* ============================================
           CUSTOM CURSOR
           ============================================ */
        .cursor {
            position: fixed;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1.5px solid var(--color-text-muted);
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease, border-color 0.3s ease, background 0.3s ease, opacity 0.2s ease;
            mix-blend-mode: difference;
        }

        .cursor.hover {
            width: 50px;
            height: 50px;
            border-color: var(--color-text-primary);
            background: rgba(128, 128, 128, 0.05);
        }

        /* ============================================
           NAVIGATION
           ============================================ */
        .navbar {
            position: relative;
            width: 100%;
            padding: clamp(1rem, 2vw, 1.8rem) 0;
            z-index: 100;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-logo {
            font-size: 1.1rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: var(--color-text-primary);
            position: relative;
            z-index: 1100;
        }

        /* ── Hamburger Button ── */
        .hamburger {
            background: none;
            border: none;
            cursor: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 8px;
            z-index: 1100;
            position: relative;
        }

        .hamburger-line {
            display: block;
            width: 28px;
            height: 1.5px;
            background-color: var(--color-text-primary);
            transform-origin: center;
            transition: none; /* gestito da GSAP */
        }

        /* ── Overlay Menu ── */
        .nav-overlay {
            position: fixed;
            inset: 0;
            /* margin-bottom: -50vw; */
            background-color: var(--color-bg);
            opacity: 1;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            clip-path: inset(0 0 100% 0); /* nascosto di default */
            pointer-events: none;
            visibility: hidden;
        }

        .nav-overlay.is-open {
            pointer-events: all;
            visibility: visible;
        }

        .nav-overlay-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
        }

        .nav-link {
            font-size: clamp(1.2rem, 3vw, 2rem);
            font-weight: 800;
            color: var(--color-text-primary);
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            opacity: 0;
            transform: translateY(30px);
            margin: 0 auto;
        }

        .nav-theme-toggle {
            margin-top: 1rem;
        }

        /* ============================================
           FOOTER
           ============================================ */
        .footer {
            padding: clamp(4rem, 8vh, 8rem) 0 clamp(2rem, 4vh, 3rem);
            border-top: 1px solid var(--color-border);
        }

        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 2rem;
        }

        .footer-brand {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            color: var(--color-text-primary);
        }

        .footer-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1.5rem;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .footer-links a {
            font-size: 0.9rem;
            font-weight: 400;
            position: relative;
            padding-bottom: 2px;
            color: var(--color-text-primary);
        }

        .footer-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--color-text-primary);
            transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .footer-links a:hover::after {
            width: 100%;
        }

        .footer-bottom {
            margin-top: clamp(3rem, 6vh, 6rem);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copy {
            font-size: 0.75rem;
            color: var(--color-text-muted);
            letter-spacing: 0.03em;
        }

        .footer-socials {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .footer-socials a {
            font-size: 0.8rem;
            color: var(--color-text-muted);
            transition: color 0.3s;
            position: relative;
            padding-bottom: 2px;
        }

        .footer-socials a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--color-text-primary);
            transition: width 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .footer-socials a:hover::after {
            width: 100%;
        }

        .footer-socials a:hover {
            color: var(--color-text-primary);
        }

        /* ============================================
           THEME TOGGLE BUTTON
           ============================================ */
        .theme-toggle {
            background: none;
            border: 1.5px solid var(--color-border);
            border-radius: 999px;
            padding: 4px 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--color-text-primary);
            transition: border-color 0.3s ease;
        }

        .theme-toggle:hover {
            border-color: var(--color-text-primary);
        }

        .theme-toggle-track {
            width: 40px;
            height: 22px;
            background: var(--color-border);
            border-radius: 999px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4px;
        }

        .theme-toggle-thumb {
            position: absolute;
            width: 16px;
            height: 16px;
            background: var(--color-text-primary);
            border-radius: 50%;
            left: 3px;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        html.dark .theme-toggle-thumb {
            transform: translateX(18px);
        }

        .icon-sun, .icon-moon {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
            transition: opacity 0.3s ease;
        }

        .icon-sun  { opacity: 1; }
        .icon-moon { opacity: 0.3; }

        html.dark .icon-sun  { opacity: 0.3; }
        html.dark .icon-moon { opacity: 1; }

        /* ============================================
           SHARED RESPONSIVE
           ============================================ */
        @media (max-width: 768px) {
            .cursor {
                display: none !important;
            }

            body {
                cursor: auto;
            }

            .hamburger {
                cursor: pointer;
            }

            .nav-link {
                font-size: clamp(1.6rem, 9vw, 2.8rem);
            }

            .footer {
                padding: clamp(3rem, 6vh, 5rem) 0 clamp(1.5rem, 3vh, 2.5rem);
            }

            .footer-grid {
                flex-direction: column;
                align-items: flex-start;
                gap: 2rem;
            }

            .footer-brand {
                font-size: clamp(2rem, 10vw, 3rem);
            }

            .footer-right {
                align-items: flex-start;
                gap: 1rem;
                width: 100%;
            }

            .footer-links {
                flex-wrap: wrap;
                gap: 0.75rem 1.5rem;
            }

            .footer-links a {
                font-size: 0.85rem;
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
                margin-top: clamp(1.5rem, 3vh, 2.5rem);
            }

            .footer-copy {
                font-size: 0.7rem;
            }

            .footer-socials {
                gap: 1rem;
            }

            .footer-socials a {
                font-size: 0.75rem;
            }
        }

        /* ── Page-specific CSS follows below ── */

