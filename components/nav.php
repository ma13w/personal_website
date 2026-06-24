<?php
/**
 * Navigation component
 *
 * Expects: $isHome (bool) - true on index.php for # anchor links
 *
 * Outputs: cursor div + <nav> with hamburger menu + overlay
 */
$isHome = $isHome ?? false;
$homeHref    = $isHome ? '#'      : './';
$projectsHref   = $isHome ? '#projects' : './projects.php';
$aboutHref   = $isHome ? '#about' : './#about';
$eduworkHref     = $isHome ? '#eduwork'   : './#eduwork';
$contactHref = './contact.php';
?>

    <!-- Custom Cursor -->
    <div class="cursor" id="cursor"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <!-- Logo/brand on the left - always visible -->
            <a href="<?= $homeHref ?>" class="navbar-logo" data-hover>mattew</a>

            <!-- Hamburger button on the right -->
            <button class="hamburger" id="hamburgerBtn" aria-label="Menu" data-hover>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
        </div>

        <!-- Menu overlay that appears on click -->
        <div class="nav-overlay" id="navOverlay">
            <div class="nav-overlay-inner">
                <a href="<?= $homeHref ?>" class="nav-link">Home</a>
                <a href="<?= $projectsHref ?>" class="nav-link">projects</a>
                <a href="<?= $aboutHref ?>" class="nav-link">About</a>
                <a href="<?= $eduworkHref ?>" class="nav-link">IT Journey</a>
                <a href="<?= $contactHref ?>" class="nav-link">Contact</a>
                <div class="nav-theme-toggle">
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                        <span class="theme-toggle-track">
                            <span class="theme-toggle-thumb"></span>
                            <svg class="icon-sun" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 2v2M12 20v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M2 12h2M20 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            <svg class="icon-moon" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
