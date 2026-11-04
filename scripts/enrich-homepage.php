<?php
/** Add the requested homepage destinations once, preserving existing cards and edits. */
if (PHP_SAPI !== 'cli') exit;
require '/var/www/html/wp-load.php';
if (get_option('cic_homepage_cards_enriched_v1')) { echo "Homepage cards already enriched.\n"; exit; }
$settings = cic_get_dept_settings();
foreach (array(
    array('title' => 'Laboratories & Facilities', 'description' => 'Explore the laboratories, equipment and spaces supporting teaching and research.', 'btn_text' => 'Explore Labs', 'btn_url' => home_url('/laboratories/'), 'icon_type' => 'flask'),
    array('title' => 'Alumni', 'description' => 'Connect with the former faculty, students and staff who are part of our community.', 'btn_text' => 'Meet Our Alumni', 'btn_url' => home_url('/alumni/'), 'icon_type' => 'book'),
) as $card) {
    $exists = false;
    foreach ($settings['cards'] as $existing) {
        if (strcasecmp(trim($existing['title']), $card['title']) === 0 || rtrim($existing['btn_url'] ?? '', '/') === rtrim($card['btn_url'], '/')) $exists = true;
    }
    if (!$exists) $settings['cards'][] = $card;
}
update_option('cic_dept_settings', $settings);
update_option('cic_homepage_cards_enriched_v1', 1);
echo "Homepage now includes Labs and Alumni alongside the existing editable cards.\n";
