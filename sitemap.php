<?php
require_once __DIR__ . '/components/helpers.php';
header('Content-Type: application/xml; charset=utf-8');

$data     = loadProjectsData();
$projects = array_filter($data['projects'], fn($p) => !empty($p['hero_description']));
$base     = 'https://calimatteo.it';
$today    = date('Y-m-d');

// Helper: escape per XML
function xmle(string $s): string {
    return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url><loc><?= xmle($base) ?>/</loc><lastmod><?= xmle($today) ?></lastmod><changefreq>monthly</changefreq><priority>1.0</priority></url>
    <url><loc><?= xmle($base) ?>/projects.php</loc><lastmod><?= xmle($today) ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
    <?php foreach (['cybersecurity','cryptography','networking','development'] as $t): ?>
    <url><loc><?= xmle($base) ?>/projects.php?topic=<?= xmle($t) ?></loc><lastmod><?= xmle($today) ?></lastmod><changefreq>weekly</changefreq><priority>0.7</priority></url>
    <?php endforeach; ?>
    <?php foreach ($projects as $p): ?>
    <url><loc><?= xmle($base) ?>/project.php?slug=<?= xmle(urlencode($p['slug'])) ?></loc><lastmod><?= xmle($today) ?></lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <?php endforeach; ?>
    <url><loc><?= xmle($base) ?>/contact.php</loc><lastmod><?= xmle($today) ?></lastmod><changefreq>yearly</changefreq><priority>0.6</priority></url>
</urlset>
