<?php
/**
 * Shared helper functions
 */

// Prevent double-declaration
if (!function_exists('e')) {
    /**
     * Escape HTML output to prevent XSS
     */
    function e(?string $str): string {
        return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Load and decode projects JSON
 * @return array Parsed JSON data
 */
function loadProjectsData(): array {
    $jsonPath = __DIR__ . '/../data/projects.json';

    if (!is_file($jsonPath) || !is_readable($jsonPath)) {
        error_log('[loadProjectsData] projects.json not found or unreadable at: ' . $jsonPath);
        return ['projects' => []];
    }

    $raw  = file_get_contents($jsonPath);

    if ($raw === false) {
        error_log('[loadProjectsData] file_get_contents failed for: ' . $jsonPath);
        return ['projects' => []];
    }

    $data = json_decode($raw, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('[loadProjectsData] JSON parse error: ' . json_last_error_msg());
        return ['projects' => []];
    }

    if (!isset($data['projects']) || !is_array($data['projects'])) {
        error_log('[loadProjectsData] Unexpected JSON structure');
        return ['projects' => []];
    }

    return $data;
}
