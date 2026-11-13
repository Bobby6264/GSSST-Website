<?php
/** Run once through Docker: promote the existing Jobs link without changing other links. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
$header = cic_get_header_settings();
$jobs = null;
foreach ($header['nav_headings'] as &$heading) {
    if (strcasecmp(trim($heading['title']), 'Jobs') === 0) $jobs = $heading;
    if (stripos($heading['title'], 'academic') === false) continue;
    $heading['subheadings'] = array_values(array_filter($heading['subheadings'] ?? array(), function ($item) use (&$jobs) {
        if (strcasecmp(trim($item['title']), 'Jobs') !== 0) return true;
        if (!$jobs) $jobs = array('title' => 'Jobs', 'url' => $item['url'], 'subheadings' => array());
        return false;
    }));
}
unset($heading);
$has_jobs = false;
foreach ($header['nav_headings'] as $heading) if (strcasecmp(trim($heading['title']), 'Jobs') === 0) $has_jobs = true;
if (!$has_jobs) {
    $jobs = $jobs ?: array('title' => 'Jobs', 'url' => home_url('/jobs/'), 'subheadings' => array());
    $insert_at = count($header['nav_headings']);
    foreach ($header['nav_headings'] as $index => $heading) if (strcasecmp(trim($heading['title']), 'Internal') === 0) { $insert_at = $index; break; }
    array_splice($header['nav_headings'], $insert_at, 0, array($jobs));
}
update_option('cic_header_settings', $header);
echo "Jobs is a main navigation item; Academics no longer contains Jobs.\n";
