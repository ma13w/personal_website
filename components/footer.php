<?php
/**
 * Footer component
 *
 * Expects: $isHome (bool) - true on index.php for # anchor links
 *
 * Outputs: <footer> with links, copyright, socials
 */
$isHome = $isHome ?? false;
$projHref   = './projects.php';
$aboutHref   = $isHome ? '#about' : './#about';
$eduHref     = $isHome ? '#edu'   : './#edu';
?>

    <footer class="footer" id="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">mattew</div>
                <div class="footer-right">
                    <ul class="footer-links">
                        <li><a href="<?= $projHref ?>" data-hover>Projects</a></li>
                        <li><a href="<?= $aboutHref ?>" data-hover>About</a></li>
                        <li><a href="<?= $eduHref ?>" data-hover>Edu & Work</a></li>
                        <li><a href="mailto:me@calimatteo.it" data-hover>Contact</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <span class="footer-copy">&copy; 2025 - <?= date('Y') ?> mattew. All rights reserved.</span>
                <ul class="footer-socials">
                    <li><a href="https://www.linkedin.com/in/mattew-cali" data-hover>LinkedIn</a></li>
                    <li><a href="https://github.com/ma13w" data-hover>GitHub</a></li>
                </ul>
            </div>
        </div>
    </footer>
